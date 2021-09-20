<?php

namespace Chuckbe\ChuckcmsModuleBooker\Models;

use Eloquent;

class Client extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cmb_clients';

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
    * A client may have many appointments.
    *
    * @var array
    */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
