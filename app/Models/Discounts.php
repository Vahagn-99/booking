<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discounts extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'discounts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'value',
        'updated',
        'property',
        'type'
    ];

    public function getFormatedStartAttribute()
    {
        return \Carbon\Carbon::createFromFormat('Y-m-d',$this->start_date)->format('d.m.Y');
    }

    public function getFormatedEndAttribute()
    {
        return \Carbon\Carbon::createFromFormat('Y-m-d',$this->end_date)->format('d.m.Y');
    }
}
