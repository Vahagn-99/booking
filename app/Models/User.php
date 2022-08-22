<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'paypal_secret_key',
        'stripe_secret_key',
        'stripe_public_key',
        'paypal_client_iden',
        'subdomain',
        'subdomain_status',
        'business_name',
        'email_business',
        'phone',
        'mobile',
        'fax',
        'country',
        'state',
        'city',
        'zip',
        'address',
        'place_lat',
        'place_lng',
        'default_check_in',
        'default_check_out',
        'default_language',
        'work_schedule_type',
        'mon_work_time',
        'tue_work_time',
        'wed_work_time',
        'thu_work_time',
        'fri_work_time',
        'sat_work_time',
        'sun_work_time',
        'agency_name',
        'account_type',
        'vendor_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function fullName()
    {
        if ($this->is_agency()) {
            return $this->agency_name;
        }
        return $this->first_name . ' ' . $this->last_name;
    }

    public function fullAddress()
    {
        return $this->country.", ".$this->city.", ".$this->address;
    }

    public function websiteSeo()
    {
        return $this->hasOne('App\Models\Customizeinfo', 'user', 'id')->where('name','website-seo');
    }

    public function websiteLogo()
    {
        return $this->hasOne('App\Models\Customizeinfo', 'user', 'id')->where('name','website-header-logo');
    }

    public function website()
    {
        return $this->hasOne('App\Models\Customizeinfo', 'user', 'id')->where('name','website-footer');
    }

    public function propertiesCount()
    {
        return Properties::where('owner', $this->id)->count();
    }

    public function properties()
    {
        $uid = $this->id;
        if ($this->is_admin()) {
            return Properties::query();
        } elseif ($this->is_agency()) {
            return Properties::query()
                ->where(function($query) use ($uid){
                    $query->where('owner', $uid);
                    $query->orWhereHas('agencyproperties', function ($q) use ($uid) {
                        $q->where('agency', $uid);
                    });
                });
        }
        return Properties::query()->where('owner', $uid);
    }

    public function is_admin()
    {
        if ($this->account_type == "admin") {
            return true;
        }
        return false;
    }

    public function is_agency()
    {
        if ($this->account_type == "agency") {
            return true;
        }
        return false;
    }

    public function is_owner()
    {
        if ($this->account_type == "owner") {
            return true;
        }
        return false;
    }
}
