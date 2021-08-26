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
        'location_id', 'date', 'time', 'duration', 'status', 'is_cancelled', 'price'
    ];

    protected $casts = [
        'json' => 'array',
    ];

    /**
     * Dynamically retrieve attributes on the model.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->getAttribute($key) ?? $this->getJson($key);
    }

     /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    public function location()
    {
        return $this->belongsTo(location::class);
    } 

    public function services()
    {
        return $this->belongsToMany(Service::class, 'appointments_services', 'appointment_id', 'service_id');
    }

    public function payment()
    {
        return $this->belongsToMany(Payment::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }


}
