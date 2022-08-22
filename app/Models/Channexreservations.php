<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Channexreservations extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'channexreservations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'reservation_iden',
        'property_iden',
        'room_iden',
        'channel_iden',
        'changed',
    ];

    public function property()
    {
        return $this->hasOne('App\Models\Channexproperties', 'id', 'property_iden');
    }

    public function room()
    {
        return $this->hasOne('App\Models\Channexrooms', 'id', 'room_iden');
    }

    public function reservation()
    {
        return $this->hasOne('App\Models\Reservations', 'id', 'reservation_iden');
    }
}
