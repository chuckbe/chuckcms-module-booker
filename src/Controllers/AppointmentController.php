<?php

namespace Chuckbe\ChuckcmsModuleBooker\Controllers;

use PDF;
use DateTime;
use Newsletter;
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
     * Return the appointment json feed.
     *
     * @param Request $request
     *
     * @return Illuminate\Response\Json
     */
    public function modal(Request $request)
    {
        $appointment = $this->appointmentRepository->find($request->id);

        $view = view('chuckcms-module-booker::backend.appointments._modal_body', compact('appointment'))->render();

        return response()->json(['html' => $view]);
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
     * Create a new appointment.
     *
     * @param Appointment $appointment
     * 
     * @return Illuminate\View\View
     */
    public function create(Request $request)
    {
        $this->validate($request, [
            'date' => 'required',
            'time' => 'required',
            'location' => 'required',
            'services' => 'required|array',
            'customer' => 'nullable',
            'create_customer' => 'required|boolean',
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'tel' => 'required',
            'promo' => 'nullable',
            'is_free_session' => 'required',
            'paid' => 'required',
            'needs_payment' => 'required',
        ]);

        if ($request->create_customer == 1) {
            $customer = $this->customerRepository->makeFromRequest($request);
        } else {
            $customer = $this->customerRepository->makeGuestFromRequest($request);
        }

        if ($customer == false || $customer == null) {
            return response()->json(['status' => 'error'], 200);
        }

        if ($customer == 'customer_exists' || $customer == 'user_exists') {
            return response()->json(['status' => $customer], 200);
        }

        $appointment = $this->appointmentRepository->makeFromRequest($request, $customer);

        if ($appointment == false || $appointment == null) {
            return response()->json(['status' => 'error'], 200);
        }

        if ($appointment == 'unavailable') {
            return response()->json(['status' => 'booked_already'], 200);
        }

        if ($request->has('promo') && $request->get('promo') == 1) {
            Newsletter::subscribeOrUpdate($customer->email, ['FNAME' => $customer->first_name, 'LNAME' => $customer->last_name]);
        }

        if (is_array($appointment->json) && (array_key_exists('subscription', $appointment->json) || array_key_exists('is_free_session', $appointment->json)) ) {
            if ($appointment->status !== 'confirmed') {
                $this->appointmentRepository->updateStatus($appointment, 'confirmed', true);
            }

            return response()->json(['status' => 'success'], 200);
        }

        if ($request->get('needs_payment') == 1) {
            $this->appointmentRepository->updateStatus($appointment, 'awaiting', true);
        }

        if ($request->get('paid') == 1) {
            $this->appointmentRepository->updateStatus($appointment, 'payment', true);
        }

        return response()->json(['status' => 'success'], 200);
    }

    /**
     * Cancel the appointment.
     *
     * @param Request $request
     *
     * @return Illuminate\Response\Json
     */
    public function cancel(Request $request)
    {
        $appointment = $this->appointmentRepository->find($request->id);

        $this->appointmentRepository->updateStatus($appointment, 'canceled', true);

        return response()->json(['status' => 'success']);
    }

    /**
     * Return the appointment invoice.
     *
     * @param Appointment $appointment
     * 
     * @return Illuminate\View\View
     */
    public function invoice(Appointment $appointment)
    {
        $pdf = PDF::loadView('chuckcms-module-booker::pdf.invoice', compact('appointment'));
        return $pdf->download($appointment->invoiceFileName);
    }

}