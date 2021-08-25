<?php

namespace Chuckbe\ChuckcmsModuleBooker\Controllers;
use Chuckbe\ChuckcmsModuleBooker\Chuck\AppointmentRepository;
use Chuckbe\ChuckcmsModuleBooker\Chuck\LocationRepository;
use Chuckbe\ChuckcmsModuleBooker\Chuck\ServiceRepository;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Chuckbe\ChuckcmsModuleBooker\Models\Appointment;
use Chuckbe\ChuckcmsModuleBooker\Models\Location;
use ChuckSite;

class BookerController extends Controller
{
    private $appointmentRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(AppointmentRepository $appointmentRepository, LocationRepository $locationRepository, ServiceRepository $serviceRepository)
    {
        $this->appointmentRepository = $appointmentRepository;
        $this->locationRepository = $locationRepository;
        $this->serviceRepository = $serviceRepository;
    }

    public function index()
    {   
        $appointments = $this->appointmentRepository->get();
        $locations = $this->locationRepository->get();
        $services = $this->serviceRepository->get();
        return view('chuckcms-module-booker::backend.dashboard.index', compact('appointments', 'locations', 'services'));
    }

    public function getServices()
    {
        $services = $this->serviceRepository->get();
        return response()->json([
            'services' => $services
        ]);
    }
}