<?php

namespace Chuckbe\ChuckcmsModuleBooker\Models;

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
        'name', 'email', 'tel', 'json'
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
}
