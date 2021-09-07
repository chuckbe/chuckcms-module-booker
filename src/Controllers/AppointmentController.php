<?php

namespace Chuckbe\ChuckcmsModuleBooker\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Chuckbe\ChuckcmsModuleBooker\Chuck\AppointmentRepository;
use Chuckbe\ChuckcmsModuleBooker\Models\Appointment;

class AppointmentController extends Controller
{
    private $appointmentRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(AppointmentRepository $appointmentRepository)
    {
        $this->appointmentRepository = $appointmentRepository;
    }

    /**
     * Return the Appointments overview page.
     *
     * @return Illuminate\View\View
     */
    public function index()
    {   
        $appointments = $this->appointmentRepository->get();

        return view('chuckcms-module-booker::backend.appointments.index', compact('appointments'));
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