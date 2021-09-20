<?php

namespace Chuckbe\ChuckcmsModuleBooker\Chuck;

use Chuckbe\ChuckcmsModuleBooker\Models\Client;
use Chuckbe\ChuckcmsModuleBooker\Models\Location;
use Illuminate\Http\Request;
use Auth;

class CustomerRepository
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Get all the locations
     *
     * @return Illuminate\Database\Eloquent\Collection
     **/
    public function get()
    {
        return $this->client->get();
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
        return $this->client->where('id', $id)->first();
    }

    /**
     * Create or update a customer.
     *
     * @param StoreCustomerRequest $request
     * 
     * @return mixed
     **/
    public function createOrUpdate(StoreCustomerRequest $request)
    {
        if ($request->has('id') && $request->has('update')) {
            return $this->update($request);
        }

        return $this->create($request);
    }

    /**
     * Create a new customer.
     *
     * @param StoreCustomerRequest $request
     * 
     * @return Chuckbe\ChuckcmsModuleBooker\Models\Client
     **/
    public function create(StoreCustomerRequest $request)
    {

        $this->client->create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'tel' => $request->get('tel'),
            'order' => (int)$request->get('order'),
            'json' => array()
        ]);

        $location->refresh();

        if (is_array($request->get('services'))) {
            $location->services()->attach($request->get('services'));   
        }

        return $location;
    }
}