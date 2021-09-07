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
    private $location;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Location $location, AppointmentRepository $appointmentRepository, LocationRepository $locationRepository, ServiceRepository $serviceRepository)
    {
        $this->location = $location;
        $this->appointmentRepository = $appointmentRepository;
        $this->locationRepository = $locationRepository;
        $this->serviceRepository = $serviceRepository;
    }

    public function appointments()
    {   
        $appointments = $this->appointmentRepository->get();
        return view('chuckcms-module-booker::backend.appointments.index', compact('appointments'));
    }

    public function getAppointmentDetail(Request $request)
    {
        $appointment = $this->appointmentRepository->find($request->appointment);
        return view('chuckcms-module-booker::backend.appointments.detail', compact('appointment'));
    }


}