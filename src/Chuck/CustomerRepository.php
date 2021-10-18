<?php

namespace Chuckbe\ChuckcmsModuleBooker\Chuck;

use Mail;
use Mollie;
use ChuckSite;
use Illuminate\Http\Request;
use Chuckbe\Chuckcms\Models\User;
use Chuckbe\Chuckcms\Chuck\UserRepository;
use Chuckbe\ChuckcmsModuleBooker\Models\Customer;
use Chuckbe\ChuckcmsModuleBooker\Requests\StoreCustomerRequest;

class CustomerRepository
{
    private $user;
    private $userRepository;
    private $customer;

    public function __construct(User $user, UserRepository $userRepository, Customer $customer)
    {
        $this->user = $user;
        $this->userRepository = $userRepository;
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
     * Make a customer based on the request
     *
     * @param Illuminate\Http\Request $request
     * 
     * @return mixed
     **/
    public function makeFromRequest(Request $request)
    {
        //see if user/customer exists with this email
        $customer = $this->customer->where('email', $request->email)->where('user_id', '!=', null)->first();

        if (!is_null($customer)) {
            return 'customer_exists';
        }

        $user = $this->user->where('email', $request->email)->first();

        if (!is_null($user)) {
            return 'user_exists';
        }

        $user = User::create([
            'name' => $request->first_name.' '.$request->last_name,
            'email' => $request->email,
            'password' => '',
            'token' => $this->userRepository->createToken(),
            'active' => 0,
        ]);
        $user->assignRole('customer');

        if ($this->customer->where('email', $request->email)->where('user_id', null)->first() !== null) {
            $customer = $this->customer->where('email', $request->email)->where('user_id', null)->first();
        } else {
            $customer = new Customer;
        }
        
        $customer->user_id = $user->id;
        $customer->first_name = $request->first_name;
        $customer->last_name = $request->last_name;
        $customer->email = $request->email;
        $customer->tel = $request->tel;

        $json = [];
        $json['general_conditions'] = true;
        $json['medical_declaration'] = true;

        $customer->json = $json;
        $customer->save();
        $customer->refresh();

        $this->sendActivationEmailForCustomer($customer, $user);

        return $customer;
    }

    /**
     * Make a customer based on the request
     *
     * @param Illuminate\Http\Request $request
     * 
     * @return mixed
     **/
    public function makeGuestFromRequest(Request $request)
    {
        if (!is_null($request->customer) && $request->customer !== 0) {
            $customer = $this->customer->where('id', $request->customer)->first();

            if (!is_null($customer)) {
                return $customer;
            }
        }
        //see if user/customer exists with this email
        $customer = $this->customer->where('email', $request->email)->where('user_id', '!=', null)->first();

        if (!is_null($customer)) {
            return 'customer_exists';
        }

        $customer = $this->customer->where('email', $request->email)->where('user_id', '=', null)->first();

        if (!is_null($customer)) {
            return $customer;
        }

        $user = $this->user->where('email', $request->email)->first();

        if (!is_null($user)) {
            return 'user_exists';
        }

        $customer = new Customer;
        $customer->user_id = null;
        $customer->first_name = $request->first_name;
        $customer->last_name = $request->last_name;
        $customer->email = $request->email;
        $customer->tel = $request->tel;

        $json = [];
        $json['general_conditions'] = true;
        $json['medical_declaration'] = true;

        $customer->json = $json;
        $customer->save();
        $customer->refresh();

        return $customer;
    }

    /**
     * Find the customer for the given id.
     *
     * @param Customer $customer
     * 
     * @return Customer $customer
     **/
    public function createMollieId(Customer $customer)
    {
        $mollieId = Mollie::api()->customers()->create([
            "name" => $customer->first_name.' '.$customer->last_name,
            "email" => $customer->email,
            "metadata" => array(
                'customer_id' => $customer->id
            )
        ]);

        $json = $customer->json;
        $json['mollie_id'] = $mollieId->id;
        $customer->json = $json;
        $customer->save();

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

        // $customer = $customer->update([
        //     'name' => $request->get('name'),
        //     'duration' => (int)$request->get('duration'),
        //     'price' => $request->get('price'),
        //     'deposit' => $request->get('deposit'),
        //     'order' => (int)$request->get('order'),
        //     'json' => array()
        // ]);

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

    /**
     * Send an activation email for the given customer.
     *
     * @param Customer $customer
     * @param User $user
     * 
     * @return void
     **/
    public function sendActivationEmailForCustomer(Customer $customer, User $user)
    {
        try {
            Mail::send('chuckcms-module-booker::frontend.emails.activation', ['customer' => $customer, 'user' => $user], function ($m) use ($user) {
                $m->from(config('chuckcms-module-booker.emails.from_email'), config('chuckcms-module-booker.emails.from_name'));
                $m->to($user->email, $user->name)->subject('Bevestiging van uw account bij '.ChuckSite::getSite('name'));
            });
        } catch (\Exception $e) {
            //dd('test 2', $e);
        }
    }
}