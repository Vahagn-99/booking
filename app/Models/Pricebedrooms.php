<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pricebedrooms extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pricebedrooms';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'property',
        'name',
        'start',
        'end',
        'min',
        'price',
        'bed_id'
    ];

    public function getFormatedStartAttribute()
    {
        return $this->start ? \Carbon\Carbon::createFromFormat('Y-m-d',$this->start)->format('d.m.Y') : '';
    }

    public function getFormatedEndAttribute()
    {
        return $this->end ? \Carbon\Carbon::createFromFormat('Y-m-d',$this->end)->format('d.m.Y') : '';
    }

    public function propertyInfo()
    {
        return $this->hasOne('App\Models\Properties', 'id', 'property');
    }

    public function getPricePeriodAttribute()
    {
        $periodprice = $this->price;
        if ($this->propertyInfo->agencywish->count() > 0) {
            $periodprice = $periodprice * 1.1;
        }

        if (count(explode('://',url()->current())) > 1) {
            if (strpos(explode('://',url()->current())[1], '.'.env('APP_MAINURL','bookingfwi.com')) !== false) {
                $url = explode('://',url()->current())[1];
                $agencydomain = explode('.'.env('APP_MAINURL','bookingfwi.com'), $url)[0];
                if ($subUser = User::where([['subdomain',$agencydomain],['subdomain_status','active']])->first()) {
                    $uid = $subUser->id;
                    if ($c = Agenciescommission::where([['agency',$uid],['property',$this->id]])->first()) {
                        $periodprice = $this->price * (1 + ($c->commission/100));
                    } else {
                        $periodprice = $this->price * 1.1;
                    }
                }
            }
        }

        $periodprice = $periodprice * (1 + ($this->propertyInfo->city_tax + $this->propertyInfo->service_charge)/100);

        return !\Cookie::has('currency') ? $this->propertyInfo->currency_sign . Currency::priceFormat($periodprice) : $this->propertyInfo->currency_sign . Currency::change($this->propertyInfo->currency,$periodprice);
    }
}
