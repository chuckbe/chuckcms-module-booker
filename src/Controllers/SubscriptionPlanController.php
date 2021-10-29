<?php

namespace Chuckbe\ChuckcmsModuleBooker\Controllers;

use DateTime;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Chuckbe\ChuckcmsModuleBooker\Models\Appointment;
use Chuckbe\ChuckcmsModuleBooker\Models\SubscriptionPlan;
use Chuckbe\ChuckcmsModuleBooker\Chuck\ServiceRepository;
use Chuckbe\ChuckcmsModuleBooker\Chuck\LocationRepository;
use Chuckbe\ChuckcmsModuleBooker\Chuck\AppointmentRepository;
use Chuckbe\ChuckcmsModuleBooker\Chuck\SubscriptionRepository;
use Chuckbe\ChuckcmsModuleBooker\Chuck\SubscriptionPlanRepository;
use Chuckbe\ChuckcmsModuleBooker\Requests\StoreSubscriptionPlanRequest;

class SubscriptionPlanController extends Controller
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
        AppointmentRepository $appointmentRepository, 
        LocationRepository $locationRepository,
        ServiceRepository $serviceRepository)
    {
        $this->subscriptionPlanRepository = $subscriptionPlanRepository;
        $this->appointmentRepository = $appointmentRepository;
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
        $subscription_plans = $this->subscriptionPlanRepository->get();
        return view('chuckcms-module-booker::backend.subscription_plans.index', compact('subscription_plans'));
    }

    /**
     * Return the subscription plan detail page for given plan.
     *
     * @param SubscriptionPlan $subscription_plan
     * 
     * @return Illuminate\View\View
     */
    public function detail(SubscriptionPlan $subscription_plan)
    {
        return view('chuckcms-module-booker::backend.subscription_plan.detail', compact('subscription_plan'));
    }

    /**
     * Return the subscription plan edit page for given plan.
     *
     * @param SubscriptionPlan $subscription_plan
     * 
     * @return Illuminate\View\View
     */
    public function edit(SubscriptionPlan $subscription_plan)
    {
        return view('chuckcms-module-booker::backend.subscription_plan.edit', compact('subscription_plan'));
    }

    /**
     * Save a new subscription plan from the request.
     *
     * @param StoreSubscriptionPlanRequest $request
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function store(StoreSubscriptionPlanRequest $request)
    {
        $this->subscriptionPlanRepository->createOrUpdate($request);

        return redirect()->route('dashboard.module.booker.subscription_plans.index');
    }

}