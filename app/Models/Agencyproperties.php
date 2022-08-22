<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agencyproperties extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'agencyproperties';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'property',
        'agency',
        'confirmed'
    ];

    public function agensyInfo()
    {
        return User::find($this->agency);
    }

    public function agensyName()
    {
        return User::find($this->agency) ? User::find($this->agency)->agency_name : '';
    }
}
