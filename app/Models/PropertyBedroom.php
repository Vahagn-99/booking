<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PropertyBedroom extends Model
{
  use HasFactory;

  protected $fillable = [
    'property',
    'bed_id'
  ];

  public function pricebedrooms()
  {
    return $this->hasMany('App\Models\Pricebedrooms', 'bed_id', 'id')->orderBy('start');
  }

  public function reservations()
  {
    return $this->hasMany('App\Models\Reservations', 'reservation_room_type', 'bed_id')->where('property', $this->property);
  }

  public function bedroom()
  {
    return RoomType::find($this->bed_id);
  }

  public function bed_name()
  {
    return ucwords($this->bedroom()->name);
  }

  public function ifHaveAditionalSofaBed()
  {
    return str_contains($this->bed_name(), 'Additional Sofa Bed');
  }

  public function getCountOfRoomsAttribute()
  {
    return $this->bedroom() ? $this->bedroom()->count_of_rooms : $this->propertyInfo->beds_min_count;
  }

  public function propertyInfo()
  {
    return $this->hasOne('App\Models\Properties', 'id', 'property');
  }

  public function pricePerPeriod($date)
  {
    if ($period = $this->pricebedrooms->first(function ($item) use ($date) {
      if (Carbon::parse($date)->between(Carbon::parse($item->start), Carbon::parse($item->end))) {
        return $item;
      }
    })) {
      return $period->price;
    }
    return $this->propertyInfo->base_rate;
  }

  public function minStay($date)
  {
    if ($period = $this->pricebedrooms->first(function ($item) use ($date) {
      if (Carbon::parse($date)->between(Carbon::parse($item->start), Carbon::parse($item->end))) {
        return $item;
      }
    })) {
      return $period->min;
    }
    return $this->propertyInfo->min_stay_base;
  }
}
