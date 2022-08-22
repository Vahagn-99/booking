<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bathrooms extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bathrooms';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'property',
        'name',
        'bath',
        'shower',
        'wc'
    ];

    public function infoText()
    {
        $infoText = [];
        if($this->bath != 0){
            array_push($infoText, $this->bath." baths");
        }
        if($this->shower != 0){
            array_push($infoText, $this->shower." showers");
        }
        if($this->wc != 0){
            array_push($infoText, $this->wc." wc");
        }

        return implode(", ", $infoText);
    }
}
