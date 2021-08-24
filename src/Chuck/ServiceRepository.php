<?php

namespace Chuckbe\ChuckcmsModuleBooker\Chuck;

use Chuckbe\ChuckcmsModuleBooker\Models\Service;
use Chuckbe\ChuckcmsModuleBooker\Models\Location;
use Illuminate\Http\Request;
use Auth;

class ServiceRepository
{
    private $appointment;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    /**
     * Get all the locations
     *
     * @var string
     **/
    public function get()
    {
        return $this->service->get();
    }
    public function find($id)
    {
        return $this->service->where('id', $id)->first();
    }
 
}