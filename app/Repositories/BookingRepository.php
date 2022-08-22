<?php

namespace App\Repositories;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\PropertyBedroom;
use App\Models\Currency;
use App\Models\User;

class BookingRepository implements BookingRepositoryInterface
{
    public function __construct()
    {
        //
    }

    public function calcTotal($property, $data)
    {
        if (strlen($data['check_in']) === 10) {
            $checkout = Carbon::parse($data['check_out'])->subDay();
            $period = CarbonPeriod::create($data['check_in'], $checkout);
        } else {
            $checkout = Carbon::parse(\DateTime::createFromFormat("d.m.y", $data['check_out']))->subDay();
            $period = CarbonPeriod::create(\DateTime::createFromFormat("d.m.y", $data['check_in']), $checkout);
        }

        $price = 0;

        // Iterate over the period
        foreach ($period as $date) {
            if (strlen($data['check_in']) === 10 && isset($data['room_id']) && $room = PropertyBedroom::where([['id',$data['room_id']],['property',$property->id]])->first()) {
                $price += $room->pricePerPeriod($date);
            } else if (strlen($data['check_in']) === 8 && isset($data['room_id']) && $room = PropertyBedroom::where([['id',$data['room_id']],['property',$property->id]])->first()) {
                $price += $room->pricePerPeriod($date);
            } else {
                $price += $property->pricePerPeriod($date);
            }
        }

        if ($property->agencywish->count() > 0) {
            if (count(explode('://',url()->current())) > 1 && strpos(explode('://',url()->current())[1], '.'.env('APP_MAINURL','bookingfwi.com')) !== false) {
                $url = explode('://',url()->current())[1];
                $agencydomain = explode('.'.env('APP_MAINURL','bookingfwi.com'), $url)[0];
                $subUser = User::where([['subdomain',$agencydomain],['subdomain_status','active']])->first();
                if ($subUser) {
                    if ($agencycommission = $property->commissions->where('agency',$subUser->id)->first()) {
                        $price = $price * (1 + (float)$agencycommission->commission / 100);
                    } else {
                        $price = $price * 1.1;
                    }
                }
            } else {
                $price = $price * 1.1;
            }
        }
        $currency = $property->currency;

        if ( isset($data['currency']) ) {
            return Currency::change($currency,$price,$data['currency']);
        }

        return !\Cookie::has('currency') ? Currency::priceFormat($price) : Currency::change($currency,$price);
    }

    public function getStartCheckOut($property, $checkin, $roomid)
    {
        $minStay = $property->minStay($checkin);

        if ($roomid && $room = PropertyBedroom::where([['bed_id',$roomid],['property',$property->id]])->first()) {
            $minStay = $room->minStay($checkin);
        }

        return Carbon::parse($checkin)->addDays($minStay)->format('d.m.Y');
    }
}
