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
use Chuckbe\ChuckcmsModuleBooker\Chuck\AppointmentRepository;
use Chuckbe\ChuckcmsModuleBooker\Chuck\SubscriptionRepository;

class SubscriptionController extends Controller
{
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
        SubscriptionRepository $subscriptionRepository, 
        AppointmentRepository $appointmentRepository, 
        LocationRepository $locationRepository,
        ServiceRepository $serviceRepository)
    {
        $this->subscriptionRepository = $subscriptionRepository;
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
        $subscriptions = $this->subscriptionRepository->getWithoutPrevious();
        return view('chuckcms-module-booker::backend.subscriptions.index', compact('subscriptions'));
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