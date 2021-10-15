<?php

namespace Chuckbe\ChuckcmsModuleBooker\Models;

use Eloquent;
use Mollie;

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
        'appointment_id', 'external_id', 'type', 'status', 'amount', 'log', 'json'
    ];

    protected $casts = [
        'log' => 'array',
        'json' => 'array',
    ];

    /**
    * A payment belongs to an appointment.
    *
    * @var array
    */
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function getPaymentUrl()
    {
        $mollie = Mollie::api()->payments()->get($this->external_id);
        return $mollie->getCheckoutUrl();
    }

    public function verify()
    {
        return $this->isPaid();
    }

    public function isPaid()
    {
        $mollie = Mollie::api()->payments()->get($this->external_id);
        if ($mollie->isPaid()) {
            return true;
        }
    }
}
