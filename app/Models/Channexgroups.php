<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Channexgroups extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'channexgroups';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'channel_iden',
        'changed',
        'min_price'
    ];

    public function properties()
    {
        return $this->hasMany('App\Models\Channexproperties', 'group_iden', 'id');
    }
}
