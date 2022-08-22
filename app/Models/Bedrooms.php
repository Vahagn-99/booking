<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bedrooms extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bedrooms';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'property',
        'name',
        'kingsize',
        'queensize',
        'double',
        'single',
        'bunk',
        'sofa'
    ];

    public function infoText()
    {
        $infoText = [];
        if($this->kingsize != 0){
            array_push($infoText, $this->kingsize." kingsize beds");
        }
        if($this->queensize != 0){
            array_push($infoText, $this->queensize." queensize beds");
        }
        if($this->double != 0){
            array_push($infoText, $this->double." double beds");
        }
        if($this->single != 0){
            array_push($infoText, $this->single." single beds");
        }
        if($this->bunk != 0){
            array_push($infoText, $this->bunk." bunk beds");
        }
        if($this->sofa != 0){
            array_push($infoText, $this->sofa." sofa");
        }

        return implode(", ", $infoText);
    }
}
