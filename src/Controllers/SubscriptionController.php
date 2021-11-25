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
     * @param Appointment $appointment
     * 
     * @return Illuminate\View\View
     */
    public function detail(Appointment $appointment)
    {
        return view('chuckcms-module-booker::backend.appointments.detail', compact('appointments'));
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
     * Return the subscription invoice.
     *
     * @param Subscription $subscription
     * 
     * @return Illuminate\View\View
     */
    public function invoice(Subscription $subscription)
    {
        $pdf = PDF::loadView('chuckcms-module-booker::pdf.subscription_invoice', compact('subscription'));
        return $pdf->download($subscription->invoiceFileName);
    }
}