<?php

namespace Chuckbe\ChuckcmsModuleBooker\Models;

use Eloquent;

class Service extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cmb_services';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type', 'name', 'duration', 'min_duration', 'max_duration', 'price', 'excluded_days', 'excluded_dates'
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

     /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    public function locations()
    {
        return $this->belongsToMany(Location::class);
    }
}
