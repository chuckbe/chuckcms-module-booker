<?php

namespace Chuckbe\ChuckcmsModuleBooker\Models;

use Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Eloquent
{
    use SoftDeletes;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cmb_services';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order', 'type', 'name', 'duration', 'min_duration', 'max_duration', 'price', 'deposit', 'disabled_weekdays', 'disabled_dates', 'json'
    ];

    protected $casts = [
        'disabled_weekdays' => 'array',
        'disabled_dates' => 'array',
        'json' => 'array',
    ];

    /**
    * A service belongs to many appointments.
    *
    * @var array
    */
    public function appointments()
    {
        return $this->belongsToMany(Appointment::class, 'appointments_services', 'service_id', 'appointment_id');
    }

    /**
    * The locations that belong to the service.
    *
    * @var array
    */
    public function locations()
    {
        return $this->belongsToMany(Location::class, 'locations_services', 'service_id', 'location_id');
    }

    /**
    * Check if the service is free or not.
    *
    * @var string
    */
    public function isFree()
    {
        return $this->price == 0;
    }

    /**
    * Check if the service is a paid service or not.
    *
    * @var string
    */
    public function isPaid()
    {
        return $this->price > 0;
    }

    /**
    * Return the formatted price.
    *
    * @var string
    */
    public function getFormattedPriceAttribute()
    {
        return '€ '.number_format($this->price, '2', ',', '.');
    }

    /**
    * Return the formatted price.
    *
    * @var string
    */
    public function getFormattedDepositAttribute()
    {
        return '€ '.number_format($this->deposit, '2', ',', '.');
    }

}
