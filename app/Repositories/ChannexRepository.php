<?php

namespace App\Repositories;

use App\Models\Brokers;
use App\Models\Channexreservations;
use App\Models\Periods;
use App\Models\Pricebedrooms;

use App\Models\Authcodes;
use App\Models\Channexgroups;
use App\Models\Channexproperties;
use App\Models\Channexrooms;
use App\Models\Channexrates;
use App\Models\Properties;
use App\Models\PropertyBedroom;
use App\Models\Reservations;
use Carbon\Carbon;

class ChannexRepository implements ChannexRepositoryInterface
{
    public function __construct()
    {
        $this->channexUsername = env('CHANNEX_USERNAME');
        $this->channexPassword = env('CHANNEX_PASSWORD');
        $this->channexURL = env('CHANNEX_URL');
    }

    public function save($request) {
        $chg = Channexgroups::where('name', 'BookingFWI')->first();
        $this->createOrUpdateChannexProperty($chg, $request);
    }

    function createOrUpdateChannexProperty($chg, $request) {
        $chp = null;
        $firstProperty = Properties::find($request->properties[0]);

        if ($request->id) {
            $chp = Channexproperties::findOrFail($request->id);
        }
        $propData = [
            'title' => $request->name,
            'currency' => $firstProperty->default_currency, // TODO: Change
            'email' => env('CHANNEX_USERNAME'),
            'zip_code' => $firstProperty->zip,
            'country' => array_key_exists($firstProperty->country,$this->countries()) ? $this->countries()[$firstProperty->country] : null,
            'state' => $firstProperty->state,
            'city' => $firstProperty->city,
            'address' => $firstProperty->address,
            'longitude' => $firstProperty->lng,
            'latitude' => $firstProperty->lat,
            'group_id' => $chg->channel_iden
        ];
        if ($request->id) {
            $updatedProperty = $this->updateProperty($chp->channel_iden, $propData);
            $chp->update([
                'name' => $request->name,
                'changed' => date('Y-m-d G:i:s'),
            ]);
        } else {
            $createdProperty = $this->createProperty($propData);
            if (!isset($createdProperty['errors'])) {
                $chp = Channexproperties::create([
                    'name' => $request->name,
                    'group_iden' => $chg->id,
                    'channel_iden' => $createdProperty['data']['id'],
                    'changed' => date('Y-m-d G:i:s'),
                ]);
            }
        }

        $this->createOrUpdateChannexRooms($chp, $request);
    }

    function createOrUpdateChannexRooms($chp, $request) {
        foreach($chp->rooms as $chr) {
            // \Log::info($chr->property_iden);

            if (!in_array($chr->property_iden, $request->properties)) {
                // Delete reservations
                foreach ($chr->reservations as $r) {
                    $r->delete();
                }
                // Delete rate plans
                foreach ($chr->rate_plans as $rp) {
                    $this->deleteRatePlan($rp->channel_iden);
                    $rp->delete();
                }
                // Delete room
                $this->deleteRoomType($chr->channel_iden);
                $chr->delete();
            }
        }

        if ($request->properties) {
            foreach ($request->properties as $propertyId) {
                $chr = null;
                $property = Properties::findOrFail($propertyId);
                $roomdata = [
                    "property_id" => $chp->channel_iden,
                    "title" => $property->name,
                    "count_of_rooms" => $property->beds_count + $property->livings_count,
                    "occ_adults" => $property->sleeps_max,
                    "occ_children" => $property->sleeps_max,
                    "occ_infants" => $property->sleeps_max,
                    "default_occupancy" => $property->sleeps_max
                ];
                if (!Channexrooms::where('property_iden', $propertyId)->first()) {
                    $baseRoom = $this->createChannexRoomType($roomdata);
                    if (!isset($baseRoom['errors'])) {
                        $chr = Channexrooms::create([
                            'property_iden' => $propertyId,
                            'channel_iden' => $baseRoom['data']['id'],
                            'channexproperty_iden' => $chp->id,
                            'changed' => date('Y-m-d G:i:s'),
                        ]);
                    }
                } else {
                    $chr = Channexrooms::where('property_iden', $propertyId)->first();
                    $this->updateRoomType($chr->channel_iden, $roomdata);
                }

                $this->createOrUpdateChannexRatePlans($chr, $request);
                $this->createOrUpdateChannexReservations($chr, $request);
            }
        }
    }

    public function createOrUpdateChannexReservations($chr, $request) {
        $reservations = Reservations::where('property', $chr->property()->first()->id);
        foreach ($chr->reservations()->get() as $chres) {
            if (!in_array($chres->reservation_iden, $reservations->pluck('id')->toArray())) {
                $chres->delete();
            }
        }

        $availabilities = [];
        foreach ($reservations->get() as $reservation) {
            $checkIn = str_replace('.', '-', $reservation->reservation_check_in);
            $checkOut = str_replace('.', '-', $reservation->reservation_check_out);
            if (Carbon::parse($checkOut)->subDay() < Carbon::today()) {
                continue;
            }

            $availabilityData = [
                'property_id' => $chr->channexProperty()->first()->channel_iden,
                'room_type_id' => $chr->channel_iden,
                "date_from" => date('Y-m-d', strtotime(Carbon::parse($checkIn) < Carbon::today() ? Carbon::today()->toDateString() : $checkIn)),
                'date_to' => date('Y-m-d', strtotime('-1 days', strtotime($checkOut))),
                "availability" => 0
            ];

            array_push($availabilities, $availabilityData);
        }
        $chResBase = $this->createAvailability($availabilities);
    }

    function createOrUpdateChannexRatePlans($chr, $request) {
        // Delete removed rate plans from Channex
        $propertyBedrooms = PropertyBedroom::where('property', $chr->property_iden);
        $periods = Periods::where('property', $chr->property_iden);
        $property = Properties::find($chr->property_iden);
        foreach ($chr->rate_plans() as $chrp) {
            if (!in_array($chrp->rate_iden, $propertyBedrooms->pluck('id')->toArray()) && !in_array($chrp->rate_iden, $periods->pluck('id')->toArray())) {
                $this->deleteRatePlan($chrp->channel_iden);
                $chrp->delete();
            }
        }

        $ratePlans = [];
        foreach ($propertyBedrooms->get() as $propertyBedroom) {
            $ratePlanBase = (object) [
                'room_name' => $propertyBedroom->bedroom()->name,
                'rate_iden' => $propertyBedroom->id
            ];

            array_push($ratePlans, $ratePlanBase);
            $brokers = Brokers::where('property', $chr->property_iden);
            foreach($brokers->get() as $broker) {
                $ratePlanBase = (object) [
                    'room_name' => $propertyBedroom->bedroom()->name . " " . $broker->broker_name,
                    'rate_iden' => $propertyBedroom->id,
                    'broker_iden' => $broker->id
                ];
                array_push($ratePlans, $ratePlanBase);
            }
        }

        foreach (Periods::select('property', 'min_bed')->where('property', $chr->property_iden)->groupBy('min_bed', 'property')->get() as $period) {
            $ratePlanBase = (object) [
                'room_name' => $period->min_bed . " Bedroom" . ($period->min_bed == 1 ? "" : "s"),
                'rate_iden' => $period->property . "-" . $period->min_bed
            ];
            array_push($ratePlans, $ratePlanBase);
            $brokers = Brokers::where('property', $chr->property_iden);
            foreach($brokers->get() as $broker) {
                $ratePlanBase = (object) [
                    'room_name' => $period->min_bed . " Bedroom" . ($period->min_bed == 1 ? " " : "s ") . $broker->broker_name,
                    'rate_iden' => $period->property . "-" . $period->min_bed,
                    'broker_iden' => $broker->id
                ];
                array_push($ratePlans, $ratePlanBase);
            }
        }

        $restrictions = [];
        $availabilities = [];
        foreach($ratePlans as $ratePlan) {
            $ratebase = [
                "property_id" => $chr->channexProperty()->first()->channel_iden,
                "title" => $ratePlan->room_name,
                "room_type_id" => $chr->channel_iden,
                "parent_rate_plan_id" => null, // TODO: Add seasons
                "options" => [
                    [
                        "occupancy" => $chr->property()->first()->sleeps_max,
                        "is_primary" => true,
                        "rate" => substr($property->price_per_night, 7) != '' ? substr($property->price_per_night, 7) : '0'
                    ]
                ],
                "currency" => $chr->property()->first()->default_currency
            ];
            $chrp = null;
            $chratebase = null;
            if (isset($ratePlan->broker_iden)) {
                $exists = $chr->rate_plans()->where('rate_iden', $ratePlan->rate_iden)->where('broker_iden', $ratePlan->broker_iden)->first() !== null;
            } else {
                $exists = $chr->rate_plans()->where('rate_iden', $ratePlan->rate_iden)->whereNull('broker_iden')->first() !== null;
            }

            if (!$exists) {
                $chratebase = $this->createRatePlan($ratebase);
                if (!isset($chratebase['errors'])) {
                    $chrp = Channexrates::create([
                        'rate_iden' => $ratePlan->rate_iden,
                        'property_iden' => $chr->property_iden,
                        'room_iden' => $chr->id,
                        'channel_iden' => $chratebase['data']['id'],
                        'broker_iden' => isset($ratePlan->broker_iden) ? $ratePlan->broker_iden : null,
                        'changed' => date('Y-m-d G:i:s'),
                    ]);
                }
            } else {
                if (isset($ratePlan->broker_iden)) {
                    $chrp = Channexrates::where('rate_iden', $ratePlan->rate_iden)->where('broker_iden', $ratePlan->broker_iden)->first();
                } else {
                    $chrp = Channexrates::where('rate_iden', $ratePlan->rate_iden)->whereNull('broker_iden')->first();
                }
                $chratebase = $this->updateRatePlan($chrp->channel_iden, $ratebase);
                if (!isset($chratebase['errors'])) {
                    $chrp->update([
                        'changed' => date('Y-m-d G:i:s')
                    ]);
                }
            }
            $restrictions = array_merge($restrictions, $this->createOrUpdateChannexRestrictions($chrp, $request));
            $availabilities = array_merge($availabilities, $this->createOrUpdateChannexAvailabilities($chrp, $request));
        }

        $this->createRestriction($restrictions);
        $this->createAvailability($availabilities);
    }

    function createOrUpdateChannexRestrictions($chrp, $request): array
    {
        $restrictions = [];
        if ($chrp) {
            $broker = $chrp->broker() ? $chrp->broker()->first() : null;
            if (str_contains($chrp->rate_iden, '-')) {
                $periods = Periods::where('property', $chrp->property_iden)->where('min_bed', explode('-', $chrp->rate_iden)[1]);
                foreach ($periods->get() as $season) {
                    if (Carbon::parse($season->end_date)->subDay() < Carbon::today()) {
                        continue;
                    }

                    $commission = 0.0;
                    if ($broker) {
                        $commission = intval(floatval($season->price) * (floatval($broker->commission_percentage) / 100.0));
                    }

                    if ($chrp->room_type()->first() && $chrp->room_type()->first()->channexProperty()->first()) {
                        $restrictionData = [
                            'property_id' => $chrp->room_type()->first()->channexProperty()->first()->channel_iden,
                            'rate_plan_id' => $chrp->channel_iden,
    //                        'room_type_id' => $chrp->room_type()->first()->channel_iden,
                            "date_from" => Carbon::parse($season->start_date) < Carbon::today() ? Carbon::today()->toDateString() : $season->start_date,
                            'date_to' => Carbon::parse($season->end_date)->subDay()->toDateString(),
                            'rate' => strval(intval(floatval($season->price) + floatval($commission))),
                            "min_stay_arrival" => $season->min_stay,
                            "min_stay_through" => $season->min_stay
                        ];
                        array_push($restrictions, (object)$restrictionData);
                    }
                }
            } else {
                $priceBedrooms = Pricebedrooms::where([['property', $chrp->property_iden], ['bed_id', $chrp->rate_iden]]);
                foreach ($priceBedrooms->get() as $season) {
                    if (Carbon::parse($season->end)->subDay() < Carbon::today()) {
                        continue;
                    }
                    $commission = 0.0;
                    if ($broker) {
                        $commission = floatval($season->price) * (floatval($broker->commission_percentage) / 100.0);
                    }
                    if ($chrp->room_type()->first() && $chrp->room_type()->first()->channexProperty()->first()) {
                        $restrictionData = [
                            'property_id' => $chrp->room_type()->first()->channexProperty()->first()->channel_iden,
                            'rate_plan_id' => $chrp->channel_iden,
    //                        'room_type_id' => $chrp->room_type()->first()->channel_iden,
                            "date_from" => Carbon::parse($season->start) < Carbon::today() ? Carbon::today()->toDateString() : $season->start,
                            'date_to' => Carbon::parse($season->end)->subDay()->toDateString(),
                            'rate' => strval(floatval($season->price) + floatval($commission)),
                            "min_stay_arrival" => $season->min,
                            "min_stay_through" => $season->min
                        ];
                        array_push($restrictions, (object) $restrictionData);
                    }
                }
            }
        }
        return $restrictions;
    }

    function createOrUpdateChannexAvailabilities($chrp, $request, $isStandardBedroom = false): array
    {
        $availabilities = [];
        if ($chrp) {
            if (str_contains($chrp->rate_iden, '-')) {
                $periods = Periods::where('property', $chrp->property_iden)->where('min_bed', explode('-', $chrp->rate_iden)[1]);
                foreach ($periods->get() as $season) {
                    if (Carbon::parse($season->end_date)->subDay() < Carbon::today()) {
                        continue;
                    }
                    $availabilityData = [
//                        'property_id' => $chr->channexProperty()->first()->channel_iden,
                        'rate_plan_id' => $chrp->channel_iden,
//                        'room_type_id' => $chrp->room_type()->first()->channel_iden,
                        "date_from" => Carbon::parse($season->start_date) < Carbon::today() ? Carbon::today()->toDateString() : $season->start_date,
                        'date_to' => Carbon::parse($season->end_date)->subDay()->toDateString(),
                        "availability" => 1
                    ];
                    array_push($availabilities, (object)$availabilityData);
                }
            } else {
                $priceBedrooms = Pricebedrooms::where([['property', $chrp->property_iden], ['bed_id', $chrp->rate_iden]]);
                foreach ($priceBedrooms->get() as $season) {
                    if (Carbon::parse($season->end)->subDay() < Carbon::today()) {
                        continue;
                    }
                    $availabilityData = [
//                        'property_id' => $chrp->room_type()->first()->channexProperty()->first()->channel_iden,
                        'rate_plan_id' => $chrp->channel_iden,
//                        'room_type_id' => $chrp->room_type()->first()->channel_iden,
                        "date_from" => Carbon::parse($season->start) < Carbon::today() ? Carbon::today()->toDateString() : $season->start,
                        'date_to' => Carbon::parse($season->end)->subDay()->toDateString(),
                        "availability" => 1
                    ];
                    array_push($availabilities, (object) $availabilityData);
                }
            }
        }
        return $availabilities;
    }

    public function processBookingRevisions($chId = null) {
        $revisions = $this->getBookingRevisions($chId);
        foreach($revisions['data'] as $revision) {
            $revision = $revision['attributes'];

            if ($revision['status'] === 'cancelled') {
                // Process cancelled reservation
                $chres = Channexreservations::where('channel_iden', $revision['unique_id'])->first();
                if ($chres != null) {
                    $res = Reservations::find($chres->reservation_iden);
                    if ($res != null) {
                        $res->delete();
                    }
                    $chres->delete();
                }

            } else if ($revision['status'] === 'new') {
                // Process new reservation
                $chres = $this->createOrUpdateReservation($revision);
            } else if ($revision['status'] === 'modified') {
                // Process updated reservation
                $chres = Channexreservations::where('channel_iden', $revision['unique_id'])->first();
                $chres = $this->createOrUpdateReservation($revision, $chres);
            }

            $this->acknowledgeRevision($revision['id']);
        }
    }

    function createOrUpdateReservation($revision, $chres = null)
    {
        $reservationRoom = $revision['rooms'][0];
        $reservationCustomer = $revision['customer'];
        $chr = Channexrooms::where('channel_iden', $reservationRoom['room_type_id'])->first();
        // TODO: Determine room type

        if ($chr) {
            $reservationData = [
                'property' => $chr->property_iden,
                'reservation_time' => date('d/m/Y H:i:s P'),
                'reservation_check_in' => date('d.m.Y', strtotime($reservationRoom['checkin_date'])),
                'reservation_check_out' => date('d.m.Y', strtotime($reservationRoom['checkout_date'])),
                'reservation_adults' => $reservationRoom['occupancy']['adults'],
                'reservation_children' => $reservationRoom['occupancy']['children'],
                'contact_first_name' => $reservationCustomer['name'] ?? '',
                'contact_last_name' => $reservationCustomer['surname'] ?? '',
                'contact_email' => $reservationCustomer['mail'] ?? '',
                'contact_phone' => $reservationCustomer['phone'] ?? '',
                'contact_address' => $reservationCustomer['address'] ?? '',
                'contact_city' => $reservationCustomer['city'] ?? '',
                // 'contact_state' => $reservationCustomer['state'],
                'contact_zip' => $reservationCustomer['zip'] ?? '',
                'contact_country' => $reservationCustomer['country'] ?? '',
                'type' => 'channex',
                'reservation_type' => 'channex',
                'status' => empty($revision['guarantee']['card_type']) ? "Not Paid" : "Paid",
                'comment' => $revision['notes'],
                'payment_status' => empty($revision['guarantee']['card_type']) ? "Not Paid" : "Paid",
                'total_price' => $revision['amount'],
                'paid' => empty($revision['guarantee']['card_type']) ? 0 : $revision['amount'],
                'service' => $revision['ota_name'],
                'reservation_type_from' => 'channex',
                'reservation_currency' => $revision['currency'],
                'reservation_room_type' => ''
            ];

            if ($reservationData) {
                if ($chres) {
                    $reservation = $chres->reservation()->first()->update($reservationData);
                    $chres->update([
                        'changed' => date('Y-m-d G:i:s')
                    ]);
                } else {
                    $reservation = Reservations::create($reservationData);
                    $chres = Channexreservations::create([
                        'reservation_iden' => $reservation->id,
                        'property_iden' => $chr->property_iden,
                        'room_iden' => $chr->id,
                        'channel_iden' => $revision['unique_id'],
                        'changed' => date('Y-m-d G:i:s')
                    ]);
                }
            }
        }

        return $chres;
    }

    // Channex API functions
    public function getTokenCh()
    {
        $data = [
            "email" => $this->channexUsername,
            "password" => $this->channexPassword
        ];
        $ch = curl_init( $this->channexURL . "/api/v1/sign_in");
        $payload = json_encode(["user"=> $data]);
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        $result = curl_exec($ch);
        curl_close($ch);
        $json = json_decode($result, true);

        if (isset($json['data'])) {
            return $json['data']['attributes']['token'];
        }
    }

    function createOrGetTokenCh()
    {
        $checkChannelUpdates = Authcodes::where('name', 'channex')->first();

        if ($checkChannelUpdates) {
            $dateCode = new \DateTime($checkChannelUpdates->timecreation);
            $dateNow = new \DateTime(date('Y-m-dTG:i:s'));
            $diffCodeNow = $dateNow->diff($dateCode);
            $hoursCodeNow = $diffCodeNow->h;
            $hoursCodeNow = $hoursCodeNow + ($diffCodeNow->days * 24);
            if (intval($hoursCodeNow) >= 24) {
                $token = $this->getTokenCh();
                $checkChannelUpdates->update([
                    'code' => $token,
                    'timecreation' => date('Y-m-dTG:i:s')
                ]);
            } else {
                $token = $checkChannelUpdates->code;
            }
        } else {
            $token = $this->getTokenCh();
            Authcodes::create([
                'name' => 'channex',
                'code' => $token,
                'timecreation' => date('Y-m-dTG:i:s')
            ]);
        }

        return $token;
    }

    public function createProperty($data)
    {
        $token = $this->createOrGetTokenCh();

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->channexURL . "/api/v1/properties");
        $payload = json_encode( array( "property"=> $data ) );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Authorization: Bearer '.$token]);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        $result = curl_exec($ch);
        curl_close($ch);
        $json = json_decode($result, true);

        if (str_contains($result, 'errors') || str_contains($result, 'warnings')) {
            // \Log::info($payload);
            // \Log::info($result);
        }

        return $json;
    }

    public function createChannexRoomType($data)
    {
        $token = $this->createOrGetTokenCh();

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->channexURL . "/api/v1/room_types");
        $payload = json_encode( array( "room_type"=> $data ) );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Authorization: Bearer '.$token]);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        $result = curl_exec($ch);
        curl_close($ch);
        $json = json_decode($result, true);
        if (str_contains($result, 'errors') || str_contains($result, 'warnings')) {
            // \Log::info($payload);
            // \Log::info($result);
        }

        return $json;
    }

    public function createRatePlan($data)
    {
        $token = $this->createOrGetTokenCh();

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->channexURL . "/api/v1/rate_plans");
        $payload = json_encode( array( "rate_plan"=> $data ) );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Authorization: Bearer '.$token]);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        $result = curl_exec($ch);
        curl_close($ch);
        $json = json_decode($result, true);
        if (str_contains($result, 'errors') || str_contains($result, 'warnings')) {
            // \Log::info($payload);
            // \Log::info($result);
        }

        return $json;
    }

    public function createRestriction($data)
    {
        $token = $this->createOrGetTokenCh();

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->channexURL . "/api/v1/restrictions");
        $payload = json_encode(array("values" => $data));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Authorization: Bearer ' . $token]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        $json = json_decode($result, true);
        if (str_contains($result, 'errors') || str_contains($result, 'warnings')) {
            // \Log::info($payload);
            // \Log::info($result);
        }

        return $json;
    }

    public function createAvailability($data)
    {
        $token = $this->createOrGetTokenCh();

        $ch = curl_init();
        $payload = json_encode(array("values" => $data));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_URL, $this->channexURL . "/api/v1/availability");
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Authorization: Bearer ' . $token]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        $json = json_decode($result, true);
        if (str_contains($result, 'errors') || str_contains($result, 'warnings')) {
            // \Log::info($payload);
            // \Log::info($result);
        }

        return $json;
    }

    public function updateProperty($chId, $data)
    {
        $token = $this->createOrGetTokenCh();

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->channexURL . "/api/v1/properties/".$chId);
        $payload = json_encode( array( "property"=> $data ) );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Authorization: Bearer ' . $token]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');

        $result = curl_exec($ch);
        curl_close($ch);
        $json = json_decode($result, true);
        if (str_contains($result, 'errors') || str_contains($result, 'warnings')) {
            // \Log::info($payload);
            // \Log::info($result);
        }

        return $json;
    }

    public function updateRoomType($chId, $data)
    {
        $token = $this->createOrGetTokenCh();

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->channexURL . "/api/v1/room_types/".$chId);
        $payload = json_encode( array( "room_type"=> $data ) );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Authorization: Bearer ' . $token]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');

        $result = curl_exec($ch);
        curl_close($ch);
        $json = json_decode($result, true);
        if (str_contains($result, 'errors') || str_contains($result, 'warnings')) {
            // \Log::info($payload);
            // \Log::info($result);
        }

        return $json;
    }

    public function updateRatePlan($chId, $data)
    {
        $token = $this->createOrGetTokenCh();

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->channexURL . "/api/v1/rate_plans/".$chId);
        $payload = json_encode( array( "rate_plan"=> $data ) );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Authorization: Bearer ' . $token]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');

        $result = curl_exec($ch);
        curl_close($ch);
        $json = json_decode($result, true);
        if (str_contains($result, 'errors') || str_contains($result, 'warnings')) {
            // \Log::info($payload);
            // \Log::info($result);
        }

        return $json;
    }

    public function deleteProperty($chId)
    {
        $token = $this->createOrGetTokenCh();

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->channexURL . "/api/v1/properties/".$chId);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Authorization: Bearer ' . $token]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        $result = curl_exec($ch);
        curl_close($ch);
    }

    public function deleteRoomType($chId)
    {
        $token = $this->createOrGetTokenCh();

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->channexURL . "/api/v1/room_types/".$chId);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Authorization: Bearer ' . $token]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        $result = curl_exec($ch);
        curl_close($ch);
    }

    public function deleteRatePlan($chId)
    {
        $token = $this->createOrGetTokenCh();

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->channexURL . "/api/v1/rate_plans/".$chId);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Authorization: Bearer ' . $token]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        $result = curl_exec($ch);
        curl_close($ch);
    }

    public function getBookingRevisions($chId = null) {
        $url = $this->channexURL . "/api/v1/booking_revisions/feed";
        $token = $this->createOrGetTokenCh();
        if ($chId) {
            $url .= "?filter[property_id]=".$chId;
        }

        $request = curl_init();
        curl_setopt($request, CURLOPT_URL, $url);
        curl_setopt($request, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Authorization: Bearer ' . $token]);
        curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($request);
        curl_close($request);
        $bookingRevisions = json_decode($response, true);
        if (str_contains($response, 'errors') || str_contains($response, 'warnings')) {
            // \Log::info($response);
        }

        return $bookingRevisions;
    }

    function acknowledgeRevision($revisionId) {
        $url = $this->channexURL . "/api/v1/booking_revisions/" . $revisionId . "/ack";
        $token = $this->createOrGetTokenCh();
        $request = curl_init();
        curl_setopt($request, CURLOPT_URL, $url);
        curl_setopt($request, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Authorization: Bearer ' . $token]);
        curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($request, CURLOPT_CUSTOMREQUEST, 'POST');
        $response = curl_exec($request);
        curl_close($request);
        $result = json_decode($response, true);
        if (str_contains($response, 'errors') || str_contains($response, 'warnings')) {
            // \Log::info($response);
        }

        return $result;
    }

    public function countries()
    {
        $countries = [
            'AF' => 'Afghanistan',
        	'AX' => 'Aland Islands',
        	'AL' => 'Albania',
        	'DZ' => 'Algeria',
        	'AS' => 'American Samoa',
        	'AD' => 'Andorra',
        	'AO' => 'Angola',
        	'AI' => 'Anguilla',
        	'AQ' => 'Antarctica',
        	'AG' => 'Antigua And Barbuda',
        	'AR' => 'Argentina',
        	'AM' => 'Armenia',
        	'AW' => 'Aruba',
        	'AU' => 'Australia',
        	'AT' => 'Austria',
        	'AZ' => 'Azerbaijan',
        	'BS' => 'Bahamas',
        	'BH' => 'Bahrain',
        	'BD' => 'Bangladesh',
        	'BB' => 'Barbados',
        	'BY' => 'Belarus',
        	'BE' => 'Belgium',
        	'BZ' => 'Belize',
        	'BJ' => 'Benin',
        	'BM' => 'Bermuda',
        	'BT' => 'Bhutan',
        	'BO' => 'Bolivia',
        	'BA' => 'Bosnia And Herzegovina',
        	'BW' => 'Botswana',
        	'BV' => 'Bouvet Island',
        	'BR' => 'Brazil',
        	'IO' => 'British Indian Ocean Territory',
        	'BN' => 'Brunei Darussalam',
        	'BG' => 'Bulgaria',
        	'BF' => 'Burkina Faso',
        	'BI' => 'Burundi',
        	'KH' => 'Cambodia',
        	'CM' => 'Cameroon',
        	'CA' => 'Canada',
        	'CV' => 'Cape Verde',
        	'KY' => 'Cayman Islands',
        	'CF' => 'Central African Republic',
        	'TD' => 'Chad',
        	'CL' => 'Chile',
        	'CN' => 'China',
        	'CX' => 'Christmas Island',
        	'CC' => 'Cocos (Keeling) Islands',
        	'CO' => 'Colombia',
        	'KM' => 'Comoros',
        	'CG' => 'Congo',
        	'CD' => 'Congo, Democratic Republic',
        	'CK' => 'Cook Islands',
        	'CR' => 'Costa Rica',
        	'CI' => 'Cote D\'Ivoire',
        	'HR' => 'Croatia',
        	'CU' => 'Cuba',
        	'CY' => 'Cyprus',
        	'CZ' => 'Czech Republic',
        	'DK' => 'Denmark',
        	'DJ' => 'Djibouti',
        	'DM' => 'Dominica',
        	'DO' => 'Dominican Republic',
        	'EC' => 'Ecuador',
        	'EG' => 'Egypt',
        	'SV' => 'El Salvador',
        	'GQ' => 'Equatorial Guinea',
        	'ER' => 'Eritrea',
        	'EE' => 'Estonia',
        	'ET' => 'Ethiopia',
        	'FK' => 'Falkland Islands (Malvinas)',
        	'FO' => 'Faroe Islands',
        	'FJ' => 'Fiji',
        	'FI' => 'Finland',
        	'FR' => 'France',
        	'GF' => 'French Guiana',
        	'PF' => 'French Polynesia',
        	'TF' => 'French Southern Territories',
        	'GA' => 'Gabon',
        	'GM' => 'Gambia',
        	'GE' => 'Georgia',
        	'DE' => 'Germany',
        	'GH' => 'Ghana',
        	'GI' => 'Gibraltar',
        	'GR' => 'Greece',
        	'GL' => 'Greenland',
        	'GD' => 'Grenada',
        	'GP' => 'Guadeloupe',
        	'GU' => 'Guam',
        	'GT' => 'Guatemala',
        	'GG' => 'Guernsey',
        	'GN' => 'Guinea',
        	'GW' => 'Guinea-Bissau',
        	'GY' => 'Guyana',
        	'HT' => 'Haiti',
        	'HM' => 'Heard Island & Mcdonald Islands',
        	'VA' => 'Holy See (Vatican City State)',
        	'HN' => 'Honduras',
        	'HK' => 'Hong Kong',
        	'HU' => 'Hungary',
        	'IS' => 'Iceland',
        	'IN' => 'India',
        	'ID' => 'Indonesia',
        	'IR' => 'Iran, Islamic Republic Of',
        	'IQ' => 'Iraq',
        	'IE' => 'Ireland',
        	'IM' => 'Isle Of Man',
        	'IL' => 'Israel',
        	'IT' => 'Italy',
        	'JM' => 'Jamaica',
        	'JP' => 'Japan',
        	'JE' => 'Jersey',
        	'JO' => 'Jordan',
        	'KZ' => 'Kazakhstan',
        	'KE' => 'Kenya',
        	'KI' => 'Kiribati',
        	'KR' => 'Korea',
        	'KW' => 'Kuwait',
        	'KG' => 'Kyrgyzstan',
        	'LA' => 'Lao People\'s Democratic Republic',
        	'LV' => 'Latvia',
        	'LB' => 'Lebanon',
        	'LS' => 'Lesotho',
        	'LR' => 'Liberia',
        	'LY' => 'Libyan Arab Jamahiriya',
        	'LI' => 'Liechtenstein',
        	'LT' => 'Lithuania',
        	'LU' => 'Luxembourg',
        	'MO' => 'Macao',
        	'MK' => 'Macedonia',
        	'MG' => 'Madagascar',
        	'MW' => 'Malawi',
        	'MY' => 'Malaysia',
        	'MV' => 'Maldives',
        	'ML' => 'Mali',
        	'MT' => 'Malta',
        	'MH' => 'Marshall Islands',
        	'MQ' => 'Martinique',
        	'MR' => 'Mauritania',
        	'MU' => 'Mauritius',
        	'YT' => 'Mayotte',
        	'MX' => 'Mexico',
        	'FM' => 'Micronesia, Federated States Of',
        	'MD' => 'Moldova',
        	'MC' => 'Monaco',
        	'MN' => 'Mongolia',
        	'ME' => 'Montenegro',
        	'MS' => 'Montserrat',
        	'MA' => 'Morocco',
        	'MZ' => 'Mozambique',
        	'MM' => 'Myanmar',
        	'NA' => 'Namibia',
        	'NR' => 'Nauru',
        	'NP' => 'Nepal',
        	'NL' => 'Netherlands',
        	'AN' => 'Netherlands Antilles',
        	'NC' => 'New Caledonia',
        	'NZ' => 'New Zealand',
        	'NI' => 'Nicaragua',
        	'NE' => 'Niger',
        	'NG' => 'Nigeria',
        	'NU' => 'Niue',
        	'NF' => 'Norfolk Island',
        	'MP' => 'Northern Mariana Islands',
        	'NO' => 'Norway',
        	'OM' => 'Oman',
        	'PK' => 'Pakistan',
        	'PW' => 'Palau',
        	'PS' => 'Palestinian Territory, Occupied',
        	'PA' => 'Panama',
        	'PG' => 'Papua New Guinea',
        	'PY' => 'Paraguay',
        	'PE' => 'Peru',
        	'PH' => 'Philippines',
        	'PN' => 'Pitcairn',
        	'PL' => 'Poland',
        	'PT' => 'Portugal',
        	'PR' => 'Puerto Rico',
        	'QA' => 'Qatar',
        	'RE' => 'Reunion',
        	'RO' => 'Romania',
        	'RU' => 'Russian Federation',
        	'RW' => 'Rwanda',
        	'BL' => 'Saint Barthelemy',
        	'SH' => 'Saint Helena',
        	'KN' => 'Saint Kitts And Nevis',
        	'LC' => 'Saint Lucia',
        	'MF' => 'Saint Martin',
        	'PM' => 'Saint Pierre And Miquelon',
        	'VC' => 'Saint Vincent And Grenadines',
        	'WS' => 'Samoa',
        	'SM' => 'San Marino',
        	'ST' => 'Sao Tome And Principe',
        	'SA' => 'Saudi Arabia',
        	'SN' => 'Senegal',
        	'RS' => 'Serbia',
        	'SC' => 'Seychelles',
        	'SL' => 'Sierra Leone',
        	'SG' => 'Singapore',
        	'SK' => 'Slovakia',
        	'SI' => 'Slovenia',
        	'SB' => 'Solomon Islands',
        	'SO' => 'Somalia',
        	'ZA' => 'South Africa',
        	'GS' => 'South Georgia And Sandwich Isl.',
        	'ES' => 'Spain',
        	'LK' => 'Sri Lanka',
        	'SD' => 'Sudan',
        	'SR' => 'Suriname',
        	'SJ' => 'Svalbard And Jan Mayen',
        	'SZ' => 'Swaziland',
        	'SE' => 'Sweden',
        	'CH' => 'Switzerland',
        	'SY' => 'Syrian Arab Republic',
        	'TW' => 'Taiwan',
        	'TJ' => 'Tajikistan',
        	'TZ' => 'Tanzania',
        	'TH' => 'Thailand',
        	'TL' => 'Timor-Leste',
        	'TG' => 'Togo',
        	'TK' => 'Tokelau',
        	'TO' => 'Tonga',
        	'TT' => 'Trinidad And Tobago',
        	'TN' => 'Tunisia',
        	'TR' => 'Turkey',
        	'TM' => 'Turkmenistan',
        	'TC' => 'Turks And Caicos Islands',
        	'TV' => 'Tuvalu',
        	'UG' => 'Uganda',
        	'UA' => 'Ukraine',
        	'AE' => 'United Arab Emirates',
        	'GB' => 'United Kingdom',
        	'US' => 'United States',
        	'UM' => 'United States Outlying Islands',
        	'UY' => 'Uruguay',
        	'UZ' => 'Uzbekistan',
        	'VU' => 'Vanuatu',
        	'VE' => 'Venezuela',
        	'VN' => 'Viet Nam',
        	'VG' => 'Virgin Islands, British',
        	'VI' => 'Virgin Islands, U.S.',
        	'WF' => 'Wallis And Futuna',
        	'EH' => 'Western Sahara',
        	'YE' => 'Yemen',
        	'ZM' => 'Zambia',
        	'ZW' => 'Zimbabwe',
        ];
        $flipped = array_flip($countries);
        $flipped['Sint Maarten'] = 'MF';
        return $flipped;
    }
}
