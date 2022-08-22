<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Channexrates extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'channexrates';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'rate_iden',
        'property_iden',
        'room_iden',
        'channel_iden',
        'broker_iden',
        'changed'
    ];

    public function room_type()
    {
        return $this->hasOne('App\Models\Channexrooms', 'id', 'room_iden');
    }

    public function broker() {
        return $this->hasOne('App\Models\Brokers', 'id', 'broker_iden');
    }
}
