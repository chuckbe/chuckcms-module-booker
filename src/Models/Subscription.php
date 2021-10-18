<?php

namespace Chuckbe\ChuckcmsModuleBooker\Models;

use Eloquent;
use ChuckModuleBooker;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscription extends Eloquent
{
    use SoftDeletes;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cmb_subscriptions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'is_expired', 'is_active', 'is_paid', 'subscription_plan_id', 'customer_id', 'expires_at', 'usage', 'weight', 'type', 'price', 'has_invoice', 'will_renew', 'json'
    ];

    protected $casts = [
        'is_expired' => 'boolean',
        'is_active' => 'boolean',
        'is_paid' => 'boolean',
        'has_invoice' => 'boolean',
        'expires_at' => 'datetime',
        'json' => 'array',
    ];

    /**
    * A subscription belongs to a subscription plan.
    *
    * @var array
    */
    public function subscriptionPlan()
    {
        return $this->belongsTo(SubscriptionPlan::class);
    }

    /**
    * A subscription belongs to a subscription plan.
    *
    * @var array
    */
    public function subscription_plan()
    {
        return $this->belongsTo(SubscriptionPlan::class);
    } 

    /**
    * A subscription belongs to a customer.
    *
    * @var array
    */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
    * A subscription may have many payments.
    *
    * @var array
    */
    public function payments()
    {
        return $this->hasMany(Payment::class);
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
    * Return the invoice file name.
    *
    * @var string
    */
    public function getInvoiceFileNameAttribute()
    {
        return 'factuur_' . ChuckModuleBooker::getSetting('invoice.prefix') . str_pad( array_key_exists('invoice_number', $this->json) ? $this->json['invoice_number'] : 'XXXX', 4, '0', STR_PAD_LEFT) . '.pdf';
    }
}
