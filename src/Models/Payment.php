<?php

namespace Chuckbe\ChuckcmsModuleBooker\Models;

use Eloquent;

class Payment extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cmb_payments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'external_id', 'type', 'status', 'amount', 'log', 'json'
    ];

    protected $casts = [
        'json' => 'array',
    ];

    /**
    * A payment belongs to many appointments.
    *
    * @var array
    */
    public function appointments()
    {
        return $this->belongsToMany(Appointment::class, 'appointments_payments', 'payment_id', 'appointment_id');
    }
}
