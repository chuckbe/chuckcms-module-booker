<?php

namespace Chuckbe\ChuckcmsModuleBooker\Models;

use Chuckbe\ChuckcmsModuleBooker\Models\Appointment;
use Eloquent;

class Customer extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cmb_customers'; //@TODO: use the config value here instead (as well as all other models)

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'tel', 'json'
    ];

    protected $casts = [
        'json' => 'array',
    ];

    /**
    * A customer may have many appointments.
    *
    * @var array
    */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * A customer belongs to a user.
     *
     * @var array
     */
    public function user()
    {
        return $this->belongsTo(config('chuckcms-module-booker.users.model'));
    }

    /**
    * A customer may have many subscriptions.
    *
    * @var array
    */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Does the customer has a mollie id.
     *
     * @return bool
     */
    public function hasMollieId()
    {
        return array_key_exists('mollie_id', $this->json);
    }

    public function getHasFreeSessionAttribute()
    {
        return $this->appointments()->where('json->is_free_session', true)->where('is_canceled', 0)->where('status', 'confirmed')->count() == 0;
    }

    public function getAvailableWeight()
    {
        $subs = $this->subscriptions()->where('is_active', 1)->whereDate('expires_at', '>', now())->get();
        $weight = 0;

        foreach ($subs as $sub) {
            if ($sub->weight == -1) {
                return -1;
            }

            $weight = $weight + $sub->weight;
        }

        return $weight;
    }

    public function getSubscriptionForWeight($weight)
    {
        $subs = $this->subscriptions()->where('is_active', 1)->whereDate('expires_at', '>', now())->get();

        foreach ($subs as $sub) {
            if ($sub->weight == -1) {
                return $sub;
            }

            if ($weight <= $sub->weight) {
                return $sub;
            }
        }

        return null;
    }

    public function getDatesWhenAvailableWeightNotAvailable()
    {
        $subs = $this->subscriptions()->where('is_active', 1)->whereDate('expires_at', '>', now())->get();

        if (count($subs) == 1) {
            $sub = $subs->first();
            $usage_per_day = $sub->subscription_plan->usage_per_day;

            $usedAppointments = Appointment::where('customer_id', $this->id)->where('is_canceled', 0)->where('status', 'confirmed')->where('json->subscription', $sub->id)->get();

            $dates = [];
            $unavailableDates = [];

            foreach ($usedAppointments as $appointment) {
                if (!array_key_exists($appointment->start->format('Ymd'), $dates)) {
                    $dates[$appointment->start->format('Y_m_d')] = 1;
                } else {
                    $dates[$appointment->start->format('Y_m_d')] = $dates[$appointment->start->format('Ymd')]++;
                }
            }

            foreach ($dates as $key => $timesUsed) {
                if ($timesUsed >= $usage_per_day) {
                    $unavailableDates[] = \Carbon\Carbon::createFromFormat('Y_m_d', $key)->format('Y-m-d');
                }
            }

            return implode(',', $unavailableDates);
        }

        return '';

        //get available weight when multiple subs available
    }

    public function getBillingAddressAttribute() 
    {
        return array_key_exists('address', $this->json) ? $this->json['address']['billing'] : array();
    }

    public function getShippingAddressAttribute() 
    {
        return array_key_exists('address', $this->json) ? $this->json['address']['shipping'] : array();
    }
}
