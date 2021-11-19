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
use Chuckbe\ChuckcmsModuleBooker\Models\Payment;
use ChuckModuleBooker;
use Newsletter;
use ChuckSite;
use Mollie;

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
            'tel' => 'required',
            'promo' => 'nullable'
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

        if ($request->has('promo') && !is_null($request->get('promo')) && $request->get('promo') == 1) {
            Newsletter::subscribeOrUpdate($customer->email, ['FNAME' => $customer->first_name, 'LNAME' => $customer->last_name]);
        }

        if (is_array($appointment->json) && (array_key_exists('subscription', $appointment->json) || array_key_exists('is_free_session', $appointment->json)) ) {
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
        if (is_array($appointment->json) && (array_key_exists('subscription', $appointment->json) || array_key_exists('is_free_session', $appointment->json))) {
            return redirect()->to(config('chuckcms-module-booker.followup.appointment'))->with('appointment', $appointment);
        }

        $payment = $appointment->payments()->where('type', 'one-off')->first();
        $mollie = Mollie::api()->payments()->get($payment->external_id);

        if ($mollie->isPaid()) {
            if (!ChuckModuleBooker::getSetting('appointment.statuses.'.$appointment->status.'.paid')) {
                $this->appointmentRepository->updateStatus($appointment, 'payment', true);
            }
        }

        if ($mollie->isCanceled() || $mollie->isFailed()) {
            $this->appointmentRepository->updateStatus($appointment, 'error', true);    
        }

        return redirect()->to(config('chuckcms-module-booker.followup.appointment'))->with('appointment', $appointment);
    }

    public function retryPayment(Appointment $appointment)
    {
        if (is_array($appointment->json) && (array_key_exists('subscription', $appointment->json) || array_key_exists('is_free_session', $appointment->json))) {
            return redirect()->to(config('chuckcms-module-booker.followup.appointment'))->with('appointment', $appointment);
        }

        $payment = $appointment->payments()->where('type', 'one-off')->first();
        $mollie = Mollie::api()->payments()->get($payment->external_id);

        if ($mollie->isPaid()) {
            if (!ChuckModuleBooker::getSetting('appointment.statuses.'.$appointment->status.'.paid')) {
                $this->appointmentRepository->updateStatus($appointment, 'payment', true);
            }

            return redirect()->to(config('chuckcms-module-booker.followup.appointment'))->with('appointment', $appointment);
        } else {
            $payment = $this->appointmentRepository->makePayment($appointment);

            if ($payment === false) {
                $this->appointmentRepository->updateStatus($appointment, 'confirmed', true);
                return redirect()->to(config('chuckcms-module-booker.followup.appointment'))->with('appointment', $appointment);
            }
        }

        return redirect()->to($payment->getCheckoutUrl());
    }

    public function subscriptionPayment(Subscription $subscription)
    {
        $payment = $subscription->payments()->first();

        if ($payment == null) {
            $payment = $this->subscriptionRepository->makePayment($subscription, $subscription->customer);
            return redirect()->to($payment->getCheckoutUrl());
        }

        $mollie = Mollie::api()->payments()->get($payment->external_id);

        if ($mollie->isPaid()) {
            if (!$subscription->is_paid) {
                $this->subscriptionRepository->updateStatus($appointment, 'payment', true);
            }

            return redirect()->to(config('chuckcms-module-booker.followup.subscription'))->with('subscription', $subscription);
        } else {
            $payment = $this->subscriptionRepository->makePayment($subscription, $subscription->customer);
        }

        return redirect()->to($payment->getCheckoutUrl());
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
        $mollie = Mollie::api()->payments()->get($payment->external_id);

        if ($mollie->isPaid()) {
            if (!$subscription->is_paid && !$subscription->is_active) {
                $this->subscriptionRepository->updateStatus($subscription, 'payment');
            }
        }

        if ($mollie->isCanceled()) {
            $this->subscriptionRepository->updateStatus($subscription, 'canceled');
        }

        if ($mollie->isFailed()) {
            $this->subscriptionRepository->updateStatus($subscription, 'failed');    
        }

        return redirect()->to(config('chuckcms-module-booker.followup.subscription'))->with('subscription', $subscription);
    }

    public function webhookMollie(Request $request)
    {
        config(['mollie.key' => ChuckSite::module('chuckcms-module-booker')->getSetting('integrations.mollie.key')]);

        if (! $request->has('id')) {
            return;
        }

        $mollie = Mollie::api()->payments()->get($request->id);
        $payment = Payment::where('external_id', $request->id)->first();

        if ($payment == null) {
            return response()->json(['status' => 'success'], 200);
        }

        $resourceType = !empty($mollie->metadata->subscription_id) ? 'subscription' : 'appointment';

        if ($resourceType == 'subscription') {
            $subscription = $this->subscriptionRepository->find($mollie->metadata->subscription_id);

            if($subscription == null) {
                return response()->json(['status' => 'success'], 200);
            }
        }

        if ($resourceType == 'appointment') {
            $appointment = $this->appointmentRepository->find($mollie->metadata->appointment_id);

            if($appointment == null) {
                return response()->json(['status' => 'success'], 200);
            }
        }

        if ($mollie->isCanceled()) {
            if ($resourceType == 'appointment') {
                $this->appointmentRepository->updateStatus($appointment, 'error', true);
            }
            
            if ($resourceType == 'subscription' && $mollie->sequenceType == 'first') {
                $this->subscriptionRepository->updateStatus($subscription, 'canceled');
            }

            return response()->json(['status' => 'success'], 200);
        }

        if ($mollie->isExpired()) {
            if ($resourceType == 'appointment') {
                $this->appointmentRepository->updateStatus($appointment, 'error', true);
            }
            
            if ($resourceType == 'subscription' && $mollie->sequenceType == 'first') {
                $this->subscriptionRepository->updateStatus($subscription, 'failed');
            }
            
            return response()->json(['status' => 'success'], 200);
        }

        if ($mollie->isPaid()) { 
            if ($resourceType == 'appointment') { 
                if (!ChuckModuleBooker::getSetting('appointment.statuses.'.$appointment->status.'.paid')) {
                    $this->appointmentRepository->updateStatus($appointment, 'payment', true);
                }
            }

            if ($resourceType == 'subscription') {
                if (!$subscription->is_paid && !$subscription->is_active) {
                    $this->subscriptionRepository->updateStatus($subscription, 'payment');
                }

                if ($mollie->hasChargebacks()) {
                    $this->subscriptionRepository->makeRecurringPayment($subscription, $subscription->customer);
                }
            }

            return response()->json(['status' => 'success'], 200);
        } 

        if ($mollie->isFailed()) {
            if ($resourceType == 'appointment') {
                $this->appointmentRepository->updateStatus($appointment, 'error', true);
            }

            if ($resourceType == 'subscription' && $mollie->sequenceType == 'first') {
                $this->subscriptionRepository->updateStatus($subscription, 'failed');
            }

            if ($resourceType == 'subscription' && $mollie->sequenceType == 'recurring') {
                $this->subscriptionRepository->makeRecurringPayment($subscription, $subscription->customer);
                $this->subscriptionRepository->updateStatus($subscription, 'failed_recurring');
            }

            return response()->json(['status' => 'success'], 200);
        }

        return response()->json(['status' => 'success'], 200);
    }
}