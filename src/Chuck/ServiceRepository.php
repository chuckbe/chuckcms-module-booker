<?php

namespace Chuckbe\ChuckcmsModuleBooker\Chuck;

use Chuckbe\ChuckcmsModuleBooker\Requests\StoreServiceRequest;
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
     * Create or update a service.
     *
     * @param StoreServiceRequest $request
     * 
     * @return mixed
     **/
    public function createOrUpdate(StoreServiceRequest $request)
    {
        if ($request->has('id') && $request->has('update')) {
            return $this->update($request);
        }

        return $this->create($request);
    }

    /**
     * Create a new service.
     *
     * @param StoreServiceRequest $request
     * 
     * @return Chuckbe\ChuckcmsModuleBooker\Models\Service
     **/
    public function create(StoreServiceRequest $request)
    {
        $service = $this->service->create([
            'name' => $request->get('name'),
            'duration' => (int)$request->get('duration'),
            'price' => $request->get('price'),
            'deposit' => $request->get('deposit'),
            'order' => (int)$request->get('order'),
            'json' => array()
        ]);

        return $service;
    }

    /**
     * Create a new service.
     *
     * @param StoreServiceRequest $request
     * 
     * @return Chuckbe\ChuckcmsModuleBooker\Models\Service
     **/
    public function update(StoreServiceRequest $request)
    {
        $service = $this->service->where('id', $request->get('id'))->first();

        $service = $service->update([
            'name' => $request->get('name'),
            'duration' => (int)$request->get('duration'),
            'price' => $request->get('price'),
            'deposit' => $request->get('deposit'),
            'order' => (int)$request->get('order'),
            'json' => array()
        ]);

        return $service;
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