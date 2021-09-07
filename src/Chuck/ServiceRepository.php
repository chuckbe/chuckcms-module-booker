<?php

namespace Chuckbe\ChuckcmsModuleBooker\Chuck;

use Chuckbe\ChuckcmsModuleBooker\Models\Service;

class ServiceRepository
{
    private $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    /**
     * Get all the services
     *
     * @return Illuminate\Database\Eloquent\Collection
     **/
    public function get()
    {
        return $this->service->get();
    }

    /**
     * Find the service for the given id.
     *
     * @param int $id
     * 
     * @return mixed
     **/
    public function find($id)
    {
        return $this->service->where('id', $id)->first();
    }

    /**
     * Delete the given service.
     *
     * @param Service $service
     * 
     * @return bool
     **/
    public function delete(Service $service)
    {
        return $service->delete();
    }
}