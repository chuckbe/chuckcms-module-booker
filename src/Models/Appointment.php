<?php

namespace Chuckbe\ChuckcmsModuleBooker\Models;

use Eloquent;

class Appointment extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cmb_appointments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'location_id', 'date', 'time', 'duration', 'status', 'is_cancelled', 'price', 'json'
    ];

    protected $casts = [
        'json' => 'array',
    ];

    /**
    * An appointment belongs to a location.
    *
    * @var array
    */
    public function location()
    {
        return $this->belongsTo(Location::class);
    } 

    /**
    * An appointment belongs to many services.
    *
    * @var array
    */
    public function services()
    {
        return $this->belongsToMany(Service::class, 'appointments_services', 'appointment_id', 'service_id');
    }

    /**
    * An appointment belongs to many payments.
    *
    * @var array
    */
    public function payments()
    {
        return $this->belongsToMany(Payment::class, 'appointments_payments', 'appointment_id', 'payment_id');
    }

    /**
    * An appointment belongs to a customer.
    *
    * @var array
    */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

}
