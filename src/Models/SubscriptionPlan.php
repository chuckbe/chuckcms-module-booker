<?php

namespace Chuckbe\ChuckcmsModuleBooker\Models;

use Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubscriptionPlan extends Eloquent
{
    use SoftDeletes;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cmb_subscription_plans';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type', 'is_active', 'is_recurring', 'name', 'months_valid', 'days_valid', 'usage_per_day', 'weight', 'price', 'disabled_weekdays', 'disabled_dates', 'json', 'order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_recurring' => 'boolean',
        'disabled_weekdays' => 'array',
        'disabled_dates' => 'array',
        'json' => 'array',
    ];

    /**
    * A subscription plan may have many subscriptions.
    *
    * @var array
    */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
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

}
