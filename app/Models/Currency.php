<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'currency';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'rate',
        'updated'
    ];

    public static function getCurrencies()
    {
        $currencies = [];
        $XML = simplexml_load_file("https://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml");
        foreach ($XML->Cube->Cube->Cube as $rate) {
            if($rate['currency'] == "USD"){
                $currencies['USD'] = floatval($rate['rate'][0]);
            }elseif ($rate['currency'] == "RUB"){
                $currencies['RUB'] = floatval($rate['rate'][0]);
            }elseif ($rate['currency'] == "CAD"){
                $currencies['CAD'] = floatval($rate['rate'][0]);
            }elseif ($rate['currency'] == "BRL"){
                $currencies['BRL'] = floatval($rate['rate'][0]);
            }elseif ($rate['currency'] == "GBP"){
                $currencies['GBP'] = floatval($rate['rate'][0]);
            }
        }

        return $currencies;
    }

    public static function priceFormat($p)
    {
        return number_format( (float) $p, 2, ".", "" );
    }

    public static function currenciesSign()
    {
        return [
            null => "&#8364;",
            "USD" => "$",
            "EUR" => "&#8364;",
            "RUB" => "&#8381;",
            "CAD" => "C$",
            "BRL" => "R$",
            "GBP" => "&#163;"
        ];
    }

    public static function change($c_from,$p,$c_to=null)
    {
        $currencies = self::getCurrencies();

        if (!$c_to) {
            $c_to = \Cookie::has('currency') ? \Cookie::get('currency') : 'EUR';
        }

        if ($c_from != $c_to) {
            if ($c_from != 'EUR') {
                // change to euro
                $p = $p / $currencies[$c_from];
            }
            if ($c_to != 'EUR') {
                $p = $p * $currencies[$c_to];
            }
        }

        return self::priceFormat($p);
    }
}
