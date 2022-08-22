<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Channexrooms extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'channexrooms';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'room_iden',
        'property_iden',
        'channel_iden',
        'channexproperty_iden',
        'changed',
    ];

    public function property()
    {
        return $this->hasOne('App\Models\Properties', 'id', 'property_iden');
    }

    public function channexProperty() {
        return $this->hasOne('App\Models\Channexproperties', 'id', 'channexproperty_iden');

    }

    public function reservations() {
        return $this->hasMany('App\Models\Channexreservations', 'room_iden', 'id');
    }

    public function rate_plans() {
        return $this->hasMany('App\Models\Channexrates', 'room_iden', 'id');
    }
}
