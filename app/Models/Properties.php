<?php

namespace App\Models;

use App\Repositories\BookingRepository;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class Properties extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'properties';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'name_fr',
        'rental_type',
        'residency_category',
        'sleeps',
        'sleeps_max',
        'area',
        'area_unit',
        'stories',
        'floor_number',
        'licence',
        'certifications',
        'headline_en',
        'summary_en',
        'description_en',
        'headline_fr',
        'summary_fr',
        'description_fr',
        'country',
        'state',
        'city',
        'zip',
        'address',
        'default_currency',
        'base_rate',
        'base_rate_kind',
        'minimum_rate',
        'downpayment',
        'damage_deposit',
        'city_tax',
        'service_charge',
        'cleaning_fee',
        'agreement_en',
        'agreement_fr',
        'cancellation_en',
        'cancellation_fr',
        'calendar',
        'lat',
        'lng',
        'min_bed',
        'min_stay_base',
        'pbedrooms_rule',
        'pbedrooms_rule_fr',
        'owner',
        'show_on_main',
        'books_item_id'
    ];

    public static function roomTypeList()
    {
        return RoomType::pluck('name','id')->toArray();
    }

    // Text fields
    public function getFullDescEnAttribute()
    {
        return str_replace("&NewLine;","<br>",$this->description_en);
    }

    public function getSmallDescEnAttribute()
    {
        return (strlen($this->full_desc_en) > 160) ? substr($this->full_desc_en, 0, 160) . "..." : $this->full_desc_en;
    }

    public function getFullDescFrAttribute()
    {
        return str_replace("&NewLine;","<br>",$this->description_fr);
    }

    public function getSmallDescFrAttribute()
    {
        return (strlen($this->full_desc_fr) > 160) ? substr($this->full_desc_fr, 0, 160) . "..." : $this->full_desc_fr;
    }

    public function getFullCancEnAttribute()
    {
        return str_replace("&NewLine;","<br>",$this->cancellation_en);
    }

    public function getSmallCancEnAttribute()
    {
        return (strlen($this->full_canc_en) > 160) ? substr($this->full_canc_en, 0, 160) . "..." : $this->full_canc_en;
    }

    public function getFullCancFrAttribute()
    {
        return str_replace("&NewLine;","<br>",$this->cancellation_fr);
    }

    public function getSmallCancFrAttribute()
    {
        return (strlen($this->full_canc_fr) > 160) ? substr($this->full_canc_fr, 0, 160) . "..." : $this->full_canc_fr;
    }

    public function getFullAgreementEnAttribute()
    {
        return str_replace("&NewLine;","<br>",$this->agreement_en);
    }

    public function getSmallAgreementEnAttribute()
    {
        return (strlen($this->full_agreement_en) > 160) ? substr($this->full_agreement_en, 0, 160) . "..." : $this->full_agreement_en;
    }

    public function getFullAgreementFrAttribute()
    {
        return str_replace("&NewLine;","<br>",$this->agreement_fr);
    }

    public function getSmallAgreementFrAttribute()
    {
        return (strlen($this->full_agreement_fr) > 160) ? substr($this->full_agreement_fr, 0, 160) . "..." : $this->full_agreement_fr;
    }

    // Address full
    public function getFullAddressAttribute()
    {
        $fullAddress = '';
        if ($this->zip != '') {
            $fullAddress .= $this->zip . ", ";
        }
        if ($this->address != '') {
            $fullAddress .= $this->address . ", ";
        }
        if ($this->city != '') {
            $fullAddress .= $this->city . ", ";
        }
        if ($this->state != '') {
            $fullAddress .= $this->state . ", ";
        }
        if ($this->country != '') {
            $fullAddress .= $this->country;
        }

        return $fullAddress;
    }

    public function getChannexRoomAttribute()
    {
        return Channexrooms::where('property_iden',$this->id)->first();
    }

    public function getRoomId($bed_count)
    {
        $propbeds = $this->propertybedrooms
            ->filter(function($item) use ($bed_count) {
                return $item->count_of_rooms >= $bed_count;
            });

        if ($this->min_bed >= $bed_count || count($propbeds) == 0) {
            return 0;
        }
        return $propbeds[0]->id;
    }

    // Relations
    public function rentalType()
    {
        return $this->hasOne('App\Models\RentalType', 'id', 'rental_type');
    }

    public function allphotos()
    {
        return $this->hasMany('App\Models\PropertyPhoto', 'property_id', 'id')->orderBy('order');
    }

    public function main_photo()
    {
        return $this->hasOne('App\Models\PropertyPhoto', 'property_id', 'id')->where('is_main',1)->orderBy('created_at','desc');
    }

    public function photos()
    {
        return $this->hasMany('App\Models\PropertyPhoto', 'property_id', 'id')->where('is_main',0)->orderBy('order');
    }

    public function amenities()
    {
        return $this->hasMany('App\Models\PropertyAmenity', 'property_id', 'id');
    }

    public function seo()
    {
        return $this->hasOne('App\Models\PropertySeo', 'property_id', 'id');
    }

    public function ownerInfo()
    {
        return $this->hasOne('App\Models\User', 'id', 'owner');
    }

    public function agencyproperties()
    {
        return $this->hasMany('App\Models\Agencyproperties', 'property', 'id');
    }

    public function agencywish()
    {
        return $this->hasMany('App\Models\Agencywish', 'property', 'id');
    }

    public function periods()
    {
        return $this->hasMany('App\Models\Periods', 'property', 'id')->orderBy('start_date');
    }

    public function propertybedrooms()
    {
        return $this->hasMany('App\Models\PropertyBedroom', 'property', 'id');
    }

    public function pricebedrooms()
    {
        return $this->hasMany('App\Models\Pricebedrooms', 'property', 'id')->orderBy('start');
    }

    public function discounts()
    {
        return $this->hasMany('App\Models\Discounts', 'property', 'id')->orderBy('start_date');
    }

    public function brokers() {
        return $this->hasMany('App\Models\Brokers', 'property', 'id');
    }

    public function cancellations()
    {
        return $this->hasMany('App\Models\Cancellation', 'property', 'id');
    }

    public function reservations()
    {
        return $this->hasMany('App\Models\Reservations', 'property', 'id');
    }

    public function commissions()
    {
        return $this->hasMany('App\Models\Agenciescommission', 'property', 'id');
    }

    public function bedrooms()
    {
        return $this->hasMany('App\Models\Bedrooms', 'property', 'id');
    }

    public function bathrooms()
    {
        return $this->hasMany('App\Models\Bathrooms', 'property', 'id');
    }

    public function livingrooms()
    {
        return $this->hasMany('App\Models\Livings', 'property', 'id');
    }

    // Rooms counts
    public function getBathsCountAttribute()
    {
        return $this->bathrooms->count();
    }

    public function getBedCountsArrayAttribute()
    {
        $bedCounts = [(int)$this->min_bed];

        foreach ($this->propertybedrooms as $pb) {
            $bedCounts []= (int)$pb->count_of_rooms;
        }
        return $bedCounts;
    }

    public function getBedsMinCountAttribute()
    {
        return $this->min_bed ? $this->min_bed : 1;
    }

    public function getBedsCountAttribute()
    {
        return $this->bedrooms->count();
    }

    public function getLivingsCountAttribute()
    {
        return $this->livingrooms->count();
    }

    public function ownerName()
    {
        $ownerName = config('app.companyName');
        if($this->ownerInfo && $this->ownerInfo->subdomain != "" && $this->ownerInfo->subdomain_status == "active"){
            $ownerName = $this->ownerInfo->business_name;
            if($this->ownerInfo->business_name == "" && $this->ownerInfo->websiteSeo) {
                $ownerName = $this->ownerInfo->websiteSeo->title;
            }
        }
        return $ownerName;
    }

    public function ownerLogo()
    {
        $ownerLogo = "https://bookingfwi.com/img/logo.png";
        if($this->ownerInfo && $this->ownerInfo->websiteLogo){
            $ownerLogo = "https://bookingfwi.com".$this->ownerInfo->websiteLogo->photo;
        }
        return $ownerLogo;
    }

    public function similars()
    {
        if (count(explode('://',url()->current())) > 1) {
            if (strpos(explode('://',url()->current())[1], '.'.env('APP_MAINURL','bookingfwi.com')) !== false) {
                $url = explode('://',url()->current())[1];
                $agencydomain = explode('.'.env('APP_MAINURL','bookingfwi.com'), $url)[0];
                if ($subUser = User::where([['subdomain',$agencydomain],['subdomain_status','active']])->first()) {
                    $uid = $subUser->id;
                    return Similar::query()
                            ->where('property',$this->id)
                            ->whereNotNull('similar_id')
                            ->with(['similar' => function ($query) use ($uid) {
                                $query->where('owner', $uid)
                                    ->orWhereHas('agencyproperties', function ($q) use ($uid) {
                                        $q->where('agency', $uid);
                                    });
                            }]);
                }
            }
        }

        return Similar::query()->where('property',$this->id)->whereNotNull('similar_id');
    }

    public function similar($order)
    {
        return $this->similars()->where('type',$order)->first();
    }

    public function reservation($date)
    {
        return Reservations::where('property',$this->id)
                ->where('reservation_check_in','!=','')
                ->where('reservation_check_out','!=','')
                ->where(function ($q) {
                    $q->whereNull('reservation_type')->orWhere('reservation_type','!=','block');
                })
                ->get(['id','reservation_check_in','reservation_check_out','added_by_agency','reservation_type','reservation_adults','reservation_children','contact_first_name','contact_last_name'])
                ->first(function($item) use ($date) {
                    if ( Carbon::parse($date)->between(Carbon::parse($item->checkin), Carbon::parse($item->checkout)) ) {
                        return $item;
                    }
                });
    }

    public function closed($date)
    {
        return Reservations::where('property',$this->id)
                ->where('reservation_check_in','!=','')
                ->where('reservation_check_out','!=','')
                ->where('reservation_type','block')
                ->get(['id','reservation_check_in','reservation_check_out','added_by_agency','reservation_type','comment','reservation_adults','reservation_children','contact_first_name','contact_last_name'])
                ->first(function($item) use ($date) {
                    if ( Carbon::parse($date)->between(Carbon::parse($item->checkin), Carbon::parse($item->checkout)) ) {
                        return $item;
                    }
                });
    }

    public static function ownersList()
    {
        $ownersList = [];
        // $ownersIds = self::pluck('id')->toArray();
        // $ownersIds []= User::where('account_type','!=','agency')->pluck('id')->toArray();
        // $owners = User::whereIn('id',$ownersIds)->get();
        $owners = User::get();

        foreach ($owners as $o) {
            $ownersList[$o->id] = $o->fullName();
        }

        return $ownersList;
    }

    // Prices
    public function getCurrencyAttribute()
    {
        return $this->default_currency ? $this->default_currency : 'EUR';
    }

    function getCurrencySignAttribute()
    {
        if (\Cookie::get('currency')) {
            return Currency::currenciesSign()[\Cookie::get('currency')];
        }
        return Currency::currenciesSign()[$this->currency];
    }

    public function getPricePerNightAttribute()
    {
        $bookingRepository = new BookingRepository();
        $bookingData['check_in'] = Carbon::today()->format("d.m.y");
        $bookingData['check_out'] = Carbon::today()->addDay()->format("d.m.y");

        return $this->currency_sign . $bookingRepository->calcTotal($this, $bookingData);
    }

    public function price_quote($checkin,$checkout,$room_id)
    {
        $bookingRepository = new BookingRepository();
        $bookingData['check_in'] = $checkin;
        $bookingData['check_out'] = $checkout;
        $bookingData['room_id'] = $room_id;
        $subtotal = $bookingRepository->calcTotal($this, $bookingData);
        $taxfee = (float) $this->cleaning_fee + $subtotal * ((float) $this->city_tax + (float) $this->service_charge)/100;
        $start = Carbon::parse(\DateTime::createFromFormat("d.m.y", $checkin))->subDay();
        $end = Carbon::parse(\DateTime::createFromFormat("d.m.y", $checkout))->subDay();
        $nights = $start->diffInDays($end);

        return [
            'nights' => $nights,
            'subtotal' => $subtotal,
            'tax_fee' => Currency::priceFormat($taxfee),
            'total' => Currency::priceFormat($subtotal + $taxfee)
        ];
    }

    public function getPriceDamageDepositAttribute()
    {
        return !\Cookie::has('currency') ? $this->currency_sign . Currency::priceFormat((float)$this->damage_deposit) : $this->currency_sign . Currency::change($this->currency,(float)$this->damage_deposit);
    }

    public function getPriceCleaningFeeAttribute()
    {
        return !\Cookie::has('currency') ? $this->currency_sign . Currency::priceFormat($this->cleaning_fee) : $this->currency_sign . Currency::change($this->currency,$this->cleaning_fee);
    }

    // periods prices
    public function pricePerPeriod($date)
    {
        if ($period = Periods::where('property',$this->id)->get(['start_date','end_date','price'])->first(function($item) use ($date) {
            if (Carbon::parse($date)->between(Carbon::parse($item->start_date), Carbon::parse($item->end_date))) {
                return $item;
            }
        })) {
            return $period->price;
        }
        return $this->base_rate;
    }

    public function pricebedPerPeriod($date)
    {
        if ($period = Pricebedrooms::where('property',$this->id)->get(['start','end','price'])
            ->first(function($item) use ($date) {
                if (Carbon::parse($date)->between(Carbon::parse($item->start), Carbon::parse($item->end))) {
                    return $item;
                }
            })) {
            return $period->price;
        }
        return $this->base_rate;
    }

    public function minStay($date)
    {
        if ($period = Periods::where('property',$this->id)->get(['start_date','end_date','min_stay'])->first(function($item) use ($date) {
            if (Carbon::parse($date)->between(Carbon::parse($item->start_date), Carbon::parse($item->end_date))) {
                return $item;
            }
        })) {
            return $period->min_stay;
        }
        return $this->min_stay_base;
    }

    public function availableDates($from, $to)
    {
        $start = Carbon::createFromFormat("d.m.y", $from);
        $end = Carbon::createFromFormat("d.m.y", $to);
        $daysCount = $end->diffInDays($start);
        $booked = json_decode($this->notAvailableDates());

        $period = CarbonPeriod::create($start, $end);
        foreach ($period as $date) {
            if (in_array($date->format('d.m.Y'),$booked)) {
                return false;
            }
        }

        return ((int)$daysCount >= (int)$this->min_stay_base);
    }

    public function scopeFilterByDate($query, $from, $to, $bath)
    {
        return $query->get()->filter(function($item) use ($from, $to, $bath) {
            if ($from == null || $to == null) {
                return (int)$item->baths_count >= (int)$bath;
            }
            return $item->availableDates($from, $to) && ((int)$item->baths_count >= (int)$bath);
        });
    }

    public function notAvailableDates()
    {
        $disabledArray = [];

        foreach ($this->reservations as $item) {
            $start = Carbon::parse($item->check_in);
            $end = Carbon::parse($item->check_out)->subDay();
            $period = CarbonPeriod::create($start, $end);
            // Iterate over the period
            foreach ($period as $date) {
                $disabledArray []= $date->format('d.m.Y');
            }
        }

        return json_encode($disabledArray);
    }

    public function reservedDates($except)
    {
        $disabledArray = [];

        foreach ($this->reservations->where('id','!=',$except) as $item) {
            $start = Carbon::parse($item->check_in)->addDay();
            $end = Carbon::parse($item->check_out)->subDay();
            $period = CarbonPeriod::create($start, $end);
            // Iterate over the period
            foreach ($period as $date) {
                $disabledArray []= $date->format('d.m.Y');
            }
        }

        return json_encode($disabledArray);
    }

    public function discountDisabled($except)
    {
        $disabledArray = [];
        foreach ($this->discounts->where('id','!=',$except) as $item) {
            $end = Carbon::parse($item->end_date)->subDay();
            $period = CarbonPeriod::create($item->start_date, $end);
            // Iterate over the period
            foreach ($period as $date) {
                $disabledArray []= $date->format('d.m.Y');
            }
        }

        return json_encode($disabledArray);
    }

    public function periodDisabled($except)
    {
        $disabledArray = [];
        foreach ($this->periods->where('id','!=',$except) as $item) {
            $end = Carbon::parse($item->end_date);
            $period = CarbonPeriod::create($item->start_date, $end);
            // Iterate over the period
            foreach ($period as $date) {
                $disabledArray []= $date->format('d.m.Y');
            }
        }

        return json_encode($disabledArray);
    }

    public function periodBedroomDisabled($bed_id,$except)
    {
        $disabledArray = [];
        foreach ($this->pricebedrooms->where('bed_id',$bed_id)->where('id','!=',$except) as $item) {
            $end = Carbon::parse($item->end);
            $period = CarbonPeriod::create($item->start, $end);
            // Iterate over the period
            foreach ($period as $date) {
                $disabledArray []= $date->format('d.m.Y');
            }
        }

        return json_encode($disabledArray);
    }

    public function info()
    {
        $info = '<small>' . $this->beds_count . ' ' . __('bedrooms') .', '. $this->baths_count . ' ' . __('bathrooms') ;
        if ($this->area) {
            $info .= ', ' . $this->area . ' ' . $this->area_unit.'<sup>2</sup>';
        }
        $info .= '</small>';

        return $info;
    }

    public function renderCalendar($dt) {
        $dt->startOfMonth();
        $headings = ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"];

        // Create the table
        $calendar = '<table class="text-center calendar-table">';
        $calendar .= '<tr>';

        // Create the calendar headings
        foreach ($headings as $heading) {
            $calendar .= '<th class="header">'.$heading.'</th>';
        }

        // Create the rest of the calendar and insert the weeknumber
        $calendar .= '</tr><tr>';

        // Day of week isn't monday, add empty preceding column(s)
        if ($dt->format('N') != 1) {
            $calendar .= '<td colspan="'.($dt->format('N') - 1).'">&nbsp;</td>';
        }

        // Get the total days in month
        $daysInMonth = $dt->daysInMonth;

        // Go over each day in the month
        for ($i = 1; $i <= $daysInMonth; $i++) {
            // Monday has been reached, start a new row
            if ($dt->format('N') == 1) {
                $calendar .= '</tr><tr>';
            }
            // Append the column
            if ($this->reservation($dt->format('Y-m-d'))) {
                $addClass = '';
                if ($this->reservation($dt->format('Y-m-d'))->reservation_check_out == $dt->format('d.m.Y')) {
                    $addClass .= 'half half-out';
                } elseif ($this->reservation($dt->format('Y-m-d'))->reservation_check_in == $dt->format('d.m.Y')) {
                    $addClass .= 'half half-in';
                }
                if ($this->reservation($dt->format('Y-m-d'))->added_by_agency == null || $this->reservation($dt->format('Y-m-d'))->added_by_agency == '') {
                    $addClass .= ' bg-primary';
                } else {
                    $addClass .= ' bg-info';
                }

                if (\Auth::user()->is_admin()|| $this->owner == \Auth::user()->id || $this->reservation($dt->format('Y-m-d'))->added_by_agency == \Auth::user()->id) {
                    $calendar .= '<td onclick="openCalendarForm(this)" class="date-wrapper-new '.$addClass.'" data-date="'.$dt->day.'"
                                data-currdate="'.$dt->format('d.m.Y').'" data-property="'.$this->id.'"
                                data-reservation="'.$this->reservation($dt->format('Y-m-d'))->id.'"
                                data-toggle="tooltip" title="'.$this->reservation($dt->format('Y-m-d'))->reservationInfo().'">'.$dt->day.'</td>';
                } else {
                    $calendar .= '<td class="date-wrapper-new '.$addClass.'" data-date="'.$dt->day.'"
                                data-currdate="'.$dt->format('d.m.Y').'" data-property="'.$this->id.'"
                                data-reservation="'.$this->reservation($dt->format('Y-m-d'))->id.'"
                                data-toggle="tooltip" title="'.$this->reservation($dt->format('Y-m-d'))->reservationInfo().'">'.$dt->day.'</td>';
                }
            } elseif ($this->closed($dt->format('Y-m-d'))) {
                $addClass = '';
                if ($this->closed($dt->format('Y-m-d'))->reservation_check_out == $dt->format('d.m.Y')) {
                    $addClass .= ' half half-out';
                } elseif ($this->closed($dt->format('Y-m-d'))->reservation_check_in == $dt->format('d.m.Y')) {
                    $addClass .= ' half half-in';
                }
                if (\Auth::user()->is_admin() || $this->owner == \Auth::user()->id || $this->closed($dt->format('Y-m-d'))->added_by_agency == \Auth::user()->id) {
                    $calendar .= '<td onclick="openCalendarForm(this)" class="date-wrapper-new bg-warning'.$addClass.'" data-date="'.$dt->day.'"
                                data-currdate="'.$dt->format('d.m.Y').'" data-property="'.$this->id.'"
                                data-reservation="'.$this->closed($dt->format('Y-m-d'))->id.'"
                                data-toggle="tooltip" title="'.$this->closed($dt->format('Y-m-d'))->reservationInfo().'">'.$dt->day.'</td>';
               } else {
                   $calendar .= '<td class="date-wrapper-new bg-warning'.$addClass.'" data-date="'.$dt->day.'"
                               data-currdate="'.$dt->format('d.m.Y').'" data-property="'.$this->id.'"
                               data-reservation="'.$this->closed($dt->format('Y-m-d'))->id.'"
                               data-toggle="tooltip" title="'.$this->closed($dt->format('Y-m-d'))->reservationInfo().'">'.$dt->day.'</td>';
               }

            } else {
                $calendar .= '<td onclick="openCalendarForm(this)" class="date-empty-wrapper" data-date="'.$dt->day.'"
                            data-currdate="'.$dt->format('d.m.Y').'" data-property="'.$this->id.'" data-reservation="">'.$dt->day.'</td>';
            }
            // Increment the date with one day
            $dt->addDay();
        }

        // Close table
        $calendar .= '</tr>';
        $calendar .= '</table>';

        return $calendar;
    }
}
