<?php

namespace Chuckbe\ChuckcmsModuleBooker\Chuck;

use Chuckbe\ChuckcmsModuleBooker\Requests\StoreSubscriptionRequest;
use Chuckbe\ChuckcmsModuleBooker\Models\Subscription;

class SubscriptionRepository
{
    private $subscription;

    public function __construct(Subscription $subscription)
    {
        $this->subscription = $subscription;
    }

    /**
     * Get all the subscriptions
     *
     * @return Illuminate\Database\Eloquent\Collection
     **/
    public function get()
    {
        return $this->subscription->get();
    }

    /**
     * Find the subscription for the given id(s).
     *
     * @param string|array $id
     * 
     * @return mixed
     **/
    public function find($id)
    {
        if (!is_array($id)) {
            return $this->subscription->where('id', $id)->first();
        }
        
        return $this->subscription->whereIn('id', $id)->get();
    }

    /**
     * Create or update a subscription.
     *
     * @param StoreSubscriptionRequest $request
     * 
     * @return mixed
     **/
    public function createOrUpdate(StoreSubscriptionRequest $request)
    {
        if ($request->has('id') && $request->has('update')) {
            return $this->update($request);
        }

        return $this->create($request);
    }

    /**
     * Create a new subscription.
     *
     * @param StoreSubscriptionRequest $request
     * 
     * @return Chuckbe\ChuckcmsModuleBooker\Models\subscription
     **/
    public function create(StoreSubscriptionRequest $request)
    {
        $subscription = $this->subscription->create([
            'name' => $request->get('name'),
            'duration' => (int)$request->get('duration'),
            'price' => $request->get('price'),
            'deposit' => $request->get('deposit'),
            'order' => (int)$request->get('order'),
            'weight' => (int)$request->get('weight'),
            'json' => array()
        ]);

        return $subscription;
    }

    /**
     * Create a new subscription.
     *
     * @param StoreSubscriptionRequest $request
     * 
     * @return Chuckbe\ChuckcmsModuleBooker\Models\subscription
     **/
    public function update(StoreSubscriptionRequest $request)
    {
        $subscription = $this->subscription->where('id', $request->get('id'))->first();

        $subscription = $subscription->update([
            'name' => $request->get('name'),
            'duration' => (int)$request->get('duration'),
            'price' => $request->get('price'),
            'deposit' => $request->get('deposit'),
            'order' => (int)$request->get('order'),
            'weight' => (int)$request->get('weight'),
            'json' => array()
        ]);

        return $subscription;
    }

    /**
     * Get the weight of the subscriptions for the given id(s).
     *
     * @param array $ids
     * 
     * @return mixed
     **/
    public function getWeightForIds(array $ids)
    {
        return $this->subscription->whereIn('id', $ids)->sum('weight');
    }

    /**
     * Delete the given subscription.
     *
     * @param Subscription $subscription
     * 
     * @return bool
     **/
    public function delete(Subscription $subscription)
    {
        return $subscription->delete();
    }
}