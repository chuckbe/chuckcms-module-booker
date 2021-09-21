<?php

namespace Chuckbe\ChuckcmsModuleBooker\Chuck;

use Chuckbe\ChuckcmsModuleBooker\Requests\StoreCustomerRequest;
use Chuckbe\ChuckcmsModuleBooker\Models\Customer;

class CustomerRepository
{
    private $customer;

    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
    }

    /**
     * Get all the services
     *
     * @return Illuminate\Database\Eloquent\Collection
     **/
    public function get()
    {
        return $this->customer->get();
    }

    /**
     * Find the customer for the given id.
     *
     * @param int $id
     * 
     * @return mixed
     **/
    public function find($id)
    {
        return $this->customer->where('id', $id)->first();
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
     * @return Chuckbe\ChuckcmsModuleBooker\Models\Customer
     **/
    public function create(StoreCustomerRequest $request)
    {
        $customer = $this->customer->create([
            'first_name' => $request->get('first_name'),
            'last_name' => $request->get('last_name'),
            'email' => $request->get('email'),
            'tel' => $request->get('tel'),
        ]);

        return $customer;
    }

    /**
     * Create a new customer.
     *
     * @param StoreCustomerRequest $request
     * 
     * @return Chuckbe\ChuckcmsModuleBooker\Models\Customer
     **/
    public function update(StoreCustomerRequest $request)
    {
        $customer = $this->customer->where('id', $request->get('id'))->first();

        $customer = $customer->update([
            'name' => $request->get('name'),
            'duration' => (int)$request->get('duration'),
            'price' => $request->get('price'),
            'deposit' => $request->get('deposit'),
            'order' => (int)$request->get('order'),
            'json' => array()
        ]);

        return $customer;
    }

    /**
     * Delete the given customer.
     *
     * @param Customer $customer
     * 
     * @return bool
     **/
    public function delete(Customer $customer)
    {
        return $customer->delete();
    }
}