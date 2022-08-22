<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class Reservations extends Model
{
    use HasFactory, SoftDeletes;

 /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'reservations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'property',
        'reservation_time',
        'reservation_check_in',
        'reservation_check_out',
        'reservation_adults',
        'reservation_children',
        'contact_first_name',
        'contact_last_name',
        'contact_email',
        'contact_phone',
        'contact_address',
        'contact_city',
        'contact_state',
        'contact_zip',
        'contact_country',
        'type',
        'reservation_type',
        'status',
        'comment',
        'payment_status',
        'payment_method',
        'transaction_id',
        'total_price',
        'paid',
        'service',
        'reservation_type_from',
        'reservation_currency',
        'reservation_room_type',
        'reservation_rental_price',
        'reservation_service_tax',
        'reservation_city_tax',
        'reservation_cleaning_fee',
        'discount_amount',
        'added_by_agency',
        'sync_status',
        'books_invoice_id',
        'books_item_id',
        'reservation_updated',
        'delete_message'
    ];

    public function getRoomNameAttribute()
    {
        $room = $this->propertyInfo->propertybedrooms->where('id', $this->reservation_room_type)->first();
        if ($room) {
            return $room->bed_name();
        }
        return $this->propertyInfo->min_bed = 1 ? $this->propertyInfo->min_bed . ' ' . 'Bedroom' : $this->propertyInfo->min_bed . ' ' . 'Bedrooms';
    }

    public function getCheckinAttribute()
    {
        if ($this->reservation_check_in != 0) {
            return Carbon::createFromFormat('d.m.Y',$this->reservation_check_in)->format('Y-m-d');
        }
        return null;
    }

    public function getCheckoutAttribute()
    {
        if ($this->reservation_check_out != 0) {
            return Carbon::createFromFormat('d.m.Y',$this->reservation_check_out)->format('Y-m-d');
        }
        return null;
    }

    public function getContactNameAttribute()
    {
        return $this->contact_first_name . " " . $this->contact_last_name;
    }

    public function getTaxFeeAttribute()
    {
        return \App\Models\Currency::priceFormat((float)$this->reservation_service_tax + (float)$this->reservation_city_tax + (float)$this->reservation_cleaning_fee);
    }

    public function getBalanceDueAttribute()
    {
        return \App\Models\Currency::priceFormat((float)$this->total_price -(float) $this->paid);
    }

    public function getContractLinkAttribute()
    {
        return 'contracts/'.$this->propertyInfo->name.'-'.$this->id.'.pdf';
    }

    public function getFullAddressAttribute()
    {
        $fullAddress = '';
        if ($this->contact_zip != '') {
            $fullAddress .= $this->contact_zip . ", ";
        }
        if ($this->contact_address != '') {
            $fullAddress .= $this->contact_address . ", ";
        }
        if ($this->contact_city != '') {
            $fullAddress .= $this->contact_city . ", ";
        }
        if ($this->contact_state != '') {
            $fullAddress .= $this->contact_state . ", ";
        }
        if ($this->contact_country != '') {
            $fullAddress .= $this->contact_country;
        }

        return $fullAddress;
    }

    public function getChannexReservationAttribute()
    {
        return Channexreservations::where('reservation_iden',$this->id)->first();
    }

    public function propertyInfo()
    {
        return $this->hasOne('App\Models\Properties', 'id', 'property');
    }

    public function reservationInfo()
    {
        if($this->reservation_type != "block") {
            $peopleCount = $this->reservation_adults;
            if($this->reservation_children != ""){
                $peopleCount += $this->reservation_children;
            }
            if ($peopleCount > 1) {
                $peopleCount = $peopleCount . " people";
            } else {
                $peopleCount = $peopleCount . " person";
            }
            if ($this->contact_first_name == "" && $this->contact_last_name == "") {
                $info = 'Reservation: no information about guest name';
            } else {
                $info = "Reservation: " . $this->contact_first_name . " " . $this->contact_last_name . ", " . $peopleCount;
            }
            return $info;
        }
        return 'Closed date' . ($this->comment ? ' ('.$this->comment.')' : '' );
    }

    public function nightsNum()
    {
        $start = Carbon::parse($this->check_in);
        $end = Carbon::parse($this->check_out);
        return $start->diffInDays($end);
    }
}
