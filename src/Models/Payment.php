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
        'appointment_id', 'subscription_id', 'external_id', 'type', 'status', 'amount', 'log', 'json'
    ];

    protected $casts = [
        'log' => 'array',
        'json' => 'array',
    ];

    /**
    * A payment may belong to an appointment.
    *
    * @var array
    */
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    /**
    * A payment may belong to an subscription.
    *
    * @var array
    */
    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
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

        if ($mollie->isOpen()) {
            return false;
        }

        if ($mollie->isCanceled()) {
            $this->status = 'canceled';
            $this->save();
            return false;
        }

        if ($mollie->isExpired()) {
            $this->status = 'expired';
            $this->save();
            return false;
        }

        if ($mollie->isFailed()) {
            $this->status = 'failed';
            $this->save();
            return false;
        }

        if ($mollie->isPending()) {
            $this->status = 'pending';
            $this->save();
            return false;
        }
        
        if ($mollie->isPaid()) {
            $this->status = 'paid';
            $this->save();
            return true;
        }
    }
}
