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
        'name', 'email', 'tel'
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
    
    public function getById($id)
    {
        return $this->where('id', $id)->first();
    }

     /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
