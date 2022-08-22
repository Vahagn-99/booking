<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agencyask extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'agencyask';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'property',
        'agency',
        'asked'
    ];

    public function propertyInfo()
    {
        return $this->hasOne('App\Models\Properties', 'id', 'property');
    }

    public function agencyInfo()
    {
        return $this->hasOne('App\Models\User', 'id', 'agency');
    }
}
