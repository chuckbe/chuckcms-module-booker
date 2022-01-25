<?php

namespace Chuckbe\ChuckcmsModuleBooker\Controllers;

use PDF;
use DateTime;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Chuckbe\ChuckcmsModuleBooker\Models\Appointment;
use Chuckbe\ChuckcmsModuleBooker\Models\Subscription;
use Chuckbe\ChuckcmsModuleBooker\Chuck\ServiceRepository;
use Chuckbe\ChuckcmsModuleBooker\Chuck\LocationRepository;
use Chuckbe\ChuckcmsModuleBooker\Chuck\CustomerRepository;
use Chuckbe\ChuckcmsModuleBooker\Chuck\AppointmentRepository;
use Chuckbe\ChuckcmsModuleBooker\Chuck\SubscriptionRepository;
use Chuckbe\ChuckcmsModuleBooker\Chuck\SubscriptionPlanRepository;
use Chuckbe\ChuckcmsModuleBooker\Requests\StoreSubscriptionRequest;
use Chuckbe\ChuckcmsModuleBooker\Requests\UpdateSubscriptionRequest;
use Chuckbe\ChuckcmsModuleBooker\Requests\CancelSubscriptionRequest;

class SubscriptionController extends Controller
{
    private $subscriptionPlanRepository;
    private $subscriptionRepository;
    private $appointmentRepository;
    private $locationRepository;
    private $serviceRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        SubscriptionPlanRepository $subscriptionPlanRepository, 
        SubscriptionRepository $subscriptionRepository, 
        AppointmentRepository $appointmentRepository, 
        CustomerRepository $customerRepository,
        LocationRepository $locationRepository,
        ServiceRepository $serviceRepository)
    {
        $this->subscriptionPlanRepository = $subscriptionPlanRepository;
        $this->subscriptionRepository = $subscriptionRepository;
        $this->appointmentRepository = $appointmentRepository;
        $this->customerRepository = $customerRepository;
        $this->locationRepository = $locationRepository;
        $this->serviceRepository = $serviceRepository;
    }

    /**
     * Return the subscriptions overview page.
     *
     * @return Illuminate\View\View
     */
    public function index()
    {   
        $subscriptions = $this->subscriptionRepository->getWithoutPrevious();
        $subscription_plans = $this->subscriptionPlanRepository->get();
        
        $customers = $this->customerRepository->get();

        return view('chuckcms-module-booker::backend.subscriptions.index', compact('subscriptions', 'subscription_plans', 'customers'));
    }

    /**
     * Return the appointments detail page for given appointment.
     *
     * @param Subscription $subscription
     * 
     * @return Illuminate\View\View
     */
    public function edit(Subscription $subscription)
    {
        return view('chuckcms-module-booker::backend.subscriptions.edit', compact('subscription'));
    }

    /**
     * Save a new subscription from the request.
     *
     * @param StoreSubscriptionRequest $request
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function store(StoreSubscriptionRequest $request)
    {
        $customer = $this->customerRepository->find($request->customer);

        if ($customer == false || $customer == null) {
            return response()->json(['status' => 'error'], 200);
        }

        $plan = $this->subscriptionPlanRepository->find($request->subscription_plan);

        $subscription = $this->subscriptionRepository->makeFromPlanAndCustomerAndRequest($plan, $customer, $request);

        if ($subscription == false || $subscription == null) {
            return response()->json(['status' => 'error'], 200);
        }

        if ($request->get('needs_payment') == 1) {
            $this->subscriptionRepository->updateStatus($subscription, 'awaiting', true);
        }

        if ($request->get('paid') == 1) {
            $this->subscriptionRepository->updateStatus($subscription, 'payment', true);
        }

        return redirect()->route('dashboard.module.booker.subscriptions.index');
    }

    /**
     * Update an existing subscription from the request.
     *
     * @param StoreSubscriptionRequest $request
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function update(UpdateSubscriptionRequest $request)
    {
        $subscription = $this->subscriptionRepository->find($request->get('id'));

        if ($subscription == false || $subscription == null) {
            return response()->json(['status' => 'error'], 200);
        }
//dd($request->get('expires_at'));

        $expires_at = explode('T', $request->get('expires_at'));

        $subscription->weight = $request->get('weight');
        $subscription->expires_at = $expires_at[0].' '.$expires_at[1].':00';
        $subscription->update();

        return redirect()->route('dashboard.module.booker.subscriptions.index');
    }

    /**
     * Return the subscription invoice.
     *
     * @param Subscription $subscription
     * 
     * @return Illuminate\View\View
     */
    public function invoice(Subscription $subscription)
    {
        return $this->subscriptionRepository->downloadInvoice($subscription);
    }

    /**
     * Return the subscription invoice.
     *
     * @param Subscription $subscription
     * 
     * @return Illuminate\View\View
     */
    public function creditNote(Subscription $subscription)
    {
        return $this->subscriptionRepository->downloadCreditNote($subscription);
    }

    /**
     * Cancel a subscription from the request.
     *
     * @param CancelSubscriptionRequest $request
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function cancel(CancelSubscriptionRequest $request)
    {
        $subscription = $this->subscriptionRepository->find($request->subscription_id);

        $email = $request->get('email') == 0 ? false : true;
        $credit = $subscription->has_invoice ? $request->get('credit') : 0;

        $this->subscriptionRepository->updateStatus($subscription, 'canceled', $email, $credit);

        return redirect()->route('dashboard.module.booker.subscriptions.index');
    }
}