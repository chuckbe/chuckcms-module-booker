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
     * Find the service for the given id(s).
     *
     * @param string|array $id
     * 
     * @return mixed
     **/
    public function find($id)
    {
        if (!is_array($id)) {
            return $this->service->where('id', $id)->first();
        }
        
        return $this->service->whereIn('id', $id)->get();
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
            'weight' => (int)$request->get('weight'),
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
            'weight' => (int)$request->get('weight'),
            'json' => array()
        ]);

        return $service;
    }

    /**
     * Get the weight of the services for the given id(s).
     *
     * @param array $ids
     * 
     * @return mixed
     **/
    public function getWeightForIds(array $ids)
    {
        return $this->service->whereIn('id', $ids)->sum('weight');
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