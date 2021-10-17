<?php

namespace Chuckbe\ChuckcmsModuleBooker\Models;

use ChuckModuleBooker;
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
        'location_id', 'customer_id', 'title', 'start', 'end', 'date', 'time', 'duration', 'status', 'is_canceled', 'has_invoice', 'price', 'json'
    ];

    protected $casts = [
        'has_invoice' => 'boolean',
        'is_canceled' => 'boolean',
        'start' => 'datetime',
        'end' => 'datetime',
        'date' => 'datetime:Y-m-d',
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
    * An appointment may have many payments.
    *
    * @var array
    */
    public function payments()
    {
        return $this->hasMany(Payment::class);
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

    public function getInvoiceFileNameAttribute()
    {
        return 'factuur_' . ChuckModuleBooker::getSetting('invoice.prefix') . str_pad($this->json['invoice_number'], 4, '0', STR_PAD_LEFT) . '.pdf';
    }

    public function getStatusLabelAttribute()
    {
        return ChuckModuleBooker::getSetting('appointment.statuses.'.$this->status.'.display_name.'.app()->getLocale());
    }

    public function getStatusShortLabelAttribute()
    {
        return ChuckModuleBooker::getSetting('appointment.statuses.'.$this->status.'.short.'.app()->getLocale());
    }

    public function getIsPaidAttribute()
    {
        return ChuckModuleBooker::getSetting('appointment.statuses.'.$this->status.'.paid');
    }

    public function getIsDepositPaidAttribute()
    {
        return ChuckModuleBooker::getSetting('appointment.statuses.'.$this->status.'.deposit_paid');
    }
}
