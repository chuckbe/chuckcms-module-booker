<?php

namespace Chuckbe\ChuckcmsModuleBooker\Controllers;

use DateTime;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Chuckbe\ChuckcmsModuleBooker\Models\Appointment;
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
        $subscriptions = $this->subscriptionRepository->get();
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

}