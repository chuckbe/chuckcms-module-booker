<?php

namespace Chuckbe\ChuckcmsModuleBooker\Chuck;

use Chuckbe\ChuckcmsModuleBooker\Requests\StoreSubscriptionPlanRequest;
use Chuckbe\ChuckcmsModuleBooker\Models\SubscriptionPlan;

class SubscriptionPlanRepository
{
    private $subscriptionPlan;

    public function __construct(SubscriptionPlan $subscriptionPlan)
    {
        $this->subscriptionPlan = $subscriptionPlan;
    }

    /**
     * Get all the subscriptions
     *
     * @return Illuminate\Database\Eloquent\Collection
     **/
    public function get()
    {
        return $this->subscriptionPlan->get();
    }

    /**
     * Find the subscription plan for the given id(s).
     *
     * @param string|array $id
     * 
     * @return mixed
     **/
    public function find($id)
    {
        if (!is_array($id)) {
            return $this->subscriptionPlan->where('id', $id)->first();
        }
        
        return $this->subscriptionPlan->whereIn('id', $id)->get();
    }

    /**
     * Create or update a subscription plan.
     *
     * @param StoreSubscriptionPlanRequest $request
     * 
     * @return mixed
     **/
    public function createOrUpdate(StoreSubscriptionPlanRequest $request)
    {
        if ($request->has('id') && $request->has('update')) {
            return $this->update($request);
        }

        return $this->create($request);
    }

    /**
     * Create a new subscription plan.
     *
     * @param StoreSubscriptionRequest $request
     * 
     * @return Chuckbe\ChuckcmsModuleBooker\Models\SubscriptionPlan
     **/
    public function create(StoreSubscriptionPlanRequest $request)
    {
        $json = [];
        if ($request->has('description') && !is_null($request->description)) {
            $json['description'] = $request->description;
        }

        $subscriptionPlan = $this->subscriptionPlan->create([
            'is_active' => $request->get('is_active') == 1 ? true : false,
            'is_recurring' => $request->get('type') == 'one-off' ? false : true,
            'type' => $request->get('type'),
            'name' => $request->get('name'),
            'months_valid' => (int)$request->get('months_valid'),
            'days_valid' => (int)$request->get('days_valid'),
            'usage_per_day' => (int)$request->get('usage_per_day'),
            'price' => $request->get('price'),
            'weight' => (int)$request->get('weight'),
            'order' => (int)$request->get('order'),
            'json' => $json
        ]);

        return $subscriptionPlan;
    }

    /**
     * Update an existing subscription plan.
     *
     * @param StoreSubscriptionPlanRequest $request
     * 
     * @return Chuckbe\ChuckcmsModuleBooker\Models\SubscriptionPlan
     **/
    public function update(StoreSubscriptionPlanRequest $request)
    {
        $subscriptionPlan = $this->subscriptionPlan->where('id', $request->get('id'))->first();

        $json = $subscriptionPlan->json;
        if ($request->has('description') && !is_null($request->description)) {
            $json['description'] = $request->description;
        }

        $subscriptionPlan = $subscriptionPlan->update([
            'is_active' => $request->get('is_active') == 1 ? true : false,
            'is_recurring' => $request->get('type') == 'one-off' ? false : true,
            'type' => $request->get('type'),
            'name' => $request->get('name'),
            'months_valid' => (int)$request->get('months_valid'),
            'days_valid' => (int)$request->get('days_valid'),
            'usage_per_day' => (int)$request->get('usage_per_day'),
            'weight' => (int)$request->get('weight'),
            'price' => $request->get('price'),
            'order' => (int)$request->get('order'),
            'json' => $json
        ]);

        return $subscriptionPlan;
    }

    /**
     * Delete the given subscription plan.
     *
     * @param Subscription $subscriptionPlan
     * 
     * @return bool
     **/
    public function delete(SubscriptionPlan $subscriptionPlan)
    {
        return $subscriptionPlan->delete();
    }
}