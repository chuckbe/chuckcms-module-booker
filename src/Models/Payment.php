<?php

namespace Chuckbe\ChuckcmsModuleBooker\Models;

use Eloquent;

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
        'external_id', 'type', 'status', 'amount', 'log'
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

}
