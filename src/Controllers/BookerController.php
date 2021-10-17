<?php

namespace Chuckbe\ChuckcmsModuleBooker\Controllers;

use Chuckbe\ChuckcmsModuleBooker\Chuck\SubscriptionRepository;
use Chuckbe\ChuckcmsModuleBooker\Chuck\AppointmentRepository;
use Chuckbe\ChuckcmsModuleBooker\Chuck\LocationRepository;
use Chuckbe\ChuckcmsModuleBooker\Chuck\ServiceRepository;
use Chuckbe\ChuckcmsModuleBooker\Chuck\CustomerRepository;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Chuckbe\ChuckcmsModuleBooker\Models\Subscription;
use Chuckbe\ChuckcmsModuleBooker\Models\Appointment;
use Chuckbe\ChuckcmsModuleBooker\Models\Location;
use ChuckSite;

class BookerController extends Controller
{
    private $subscriptionRepository;
    private $appointmentRepository;
    private $customerRepository;
    private $location;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        Location $location, 
        SubscriptionRepository $subscriptionRepository, 
        AppointmentRepository $appointmentRepository, 
        CustomerRepository $customerRepository, 
        LocationRepository $locationRepository, 
        ServiceRepository $serviceRepository)
    {
        $this->location = $location;
        $this->subscriptionRepository = $subscriptionRepository;
        $this->appointmentRepository = $appointmentRepository;
        $this->customerRepository = $customerRepository;
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

    public function getAvailableDates(Request $request)
    {
        $this->validate($request, [
            'location' => 'required',
            'services' => 'required|array'
        ]);

        $availableDatesAndTimeslots = $this->appointmentRepository->getAvailableDates($request->location, $request->services);
        
        if (!$availableDatesAndTimeslots) {
            return response()->json(['status' => 'error'], 200);
        }

        return response()->json([
            'status' => 'success', 
            'availability' => $availableDatesAndTimeslots
        ], 200);
    }

    public function makeAppointment(Request $request)
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
            'tel' => 'required'
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

        if (array_key_exists('subscription', $appointment->json)) {
            return response()->json([
                'status' => 'success', 
                'redirect' => route('module.booker.checkout.followup', ['appointment' => $appointment->id])
            ], 200);
        }

        return response()->json([
            'status' => 'success', 
            'redirect' => $appointment->payments()->first()->getPaymentUrl()
        ], 200);
    }

    public function followup(Appointment $appointment)
    {
        if (array_key_exists('subscription', $appointment->json)) {
            $this->appointmentRepository->updateStatus($appointment, 'confirmed');
            return redirect()->to(config('chuckcms-module-booker.followup.appointment'))->with('appointment', $appointment);
        }

        $payment = $appointment->payments()->where('type', 'one-off')->first();

        if ($payment->isPaid()) {
            $this->appointmentRepository->updateStatus($appointment, 'payment');
        }

        return redirect()->to(config('chuckcms-module-booker.followup.appointment'))->with('appointment', $appointment);
    }

    public function makeSubscription(Request $request)
    {
        $this->validate($request, [
            'subscription_plan' => 'required',
            'customer' => 'nullable',
            'create_customer' => 'required|boolean',
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'tel' => 'required'
        ]);

        $customer = $this->customerRepository->makeFromRequest($request);

        if ($customer == false || $customer == null) {
            return response()->json(['status' => 'error'], 200);
        }

        if ($customer == 'customer_exists' || $customer == 'user_exists') {
            return response()->json(['status' => $customer], 200);
        }

        $subscription = $this->subscriptionRepository->makeFromRequest($request, $customer);

        if ($subscription == false || $subscription == null) {
            return response()->json(['status' => 'error'], 200);
        }

        return response()->json([
            'status' => 'success', 
            'redirect' => $subscription->payments()->first()->getPaymentUrl()
        ], 200);
    }

    public function subscriptionFollowup(Subscription $subscription)
    {
        $payment = $subscription->payments()->where('type', 'first')->first();

        if ($payment->isPaid()) {
            $this->subscriptionRepository->updateStatus($subscription, 'payment');
        }

        return redirect()->to(config('chuckcms-module-booker.followup.subscription'))->with('subscription', $subscription);
    }
}