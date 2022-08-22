<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Channexproperties extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'channexproperties';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'group_iden',
        'name',
        'channel_iden',
        'changed'
    ];

    public function group()
    {
        return $this->hasOne('App\Models\Channexgroups', 'id', 'group_iden');
    }

    public function rooms()
    {
        return $this->hasMany('App\Models\Channexrooms', 'channexproperty_iden', 'id');
    }

    public function rate_plans()
    {
        return $this->hasMany('App\Models\Channexrates', 'property_iden', 'id');
    }

    public static function propTypes()
    {
        return [
            'apart_hotel',
            'apartment',
            'boat',
            'camping',
            'capsule_hotel',
            'chalet',
            'country_house',
            'farm_stay',
            'guest_house',
            'holiday_home',
            'holiday_park',
            'homestay',
            'hostel',
            'hotel',
            'inn',
            'lodge',
            'motel',
            'resort',
            'riad',
            'ryokan',
            'tent',
            'villa'
        ];
    }
}
