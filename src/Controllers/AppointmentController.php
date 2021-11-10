<?php

namespace Chuckbe\ChuckcmsModuleBooker\Controllers;

use DateTime;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Chuckbe\ChuckcmsModuleBooker\Models\Appointment;
use Chuckbe\ChuckcmsModuleBooker\Chuck\ServiceRepository;
use Chuckbe\ChuckcmsModuleBooker\Chuck\LocationRepository;
use Chuckbe\ChuckcmsModuleBooker\Chuck\CustomerRepository;
use Chuckbe\ChuckcmsModuleBooker\Chuck\AppointmentRepository;

class AppointmentController extends Controller
{
    private $appointmentRepository;
    private $locationRepository;
    private $serviceRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        AppointmentRepository $appointmentRepository, 
        LocationRepository $locationRepository,
        CustomerRepository $customerRepository,
        ServiceRepository $serviceRepository)
    {
        $this->appointmentRepository = $appointmentRepository;
        $this->locationRepository = $locationRepository;
        $this->customerRepository = $customerRepository;
        $this->serviceRepository = $serviceRepository;
    }

    /**
     * Return the Appointments overview page.
     *
     * @return Illuminate\View\View
     */
    public function index()
    {
        $locations = $this->locationRepository->get();
        if (count($locations) == 1) {
            $services = $locations->first()->services()->get();
        } else {
            $services = $this->serviceRepository->get();
        }
        $customers = $this->customerRepository->get();

        return view('chuckcms-module-booker::backend.appointments.index', compact('locations', 'services', 'customers'));
    }

    /**
     * Return the appointments json feed.
     *
     * @param Request $request
     *
     * @return Illuminate\Response\Json
     */
    public function json(Request $request)
    {
        $appointments = $this->appointmentRepository->betweenDates(new DateTime($request->start), new DateTime($request->end), 'asc', ['id', 'title', 'start', 'end', 'time', 'duration', 'status', 'weight', 'price']);

        return response()->json($appointments);
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