<?php

namespace Chuckbe\ChuckcmsModuleBooker\Chuck;

use Chuckbe\ChuckcmsModuleBooker\Chuck\BookerFormRepository;
use Chuckbe\ChuckcmsModuleBooker\Chuck\LocationRepository;
use Chuckbe\ChuckcmsModuleBooker\Chuck\ServiceRepository;
use Chuckbe\ChuckcmsModuleBooker\Models\Appointment;
use Chuckbe\ChuckcmsModuleBooker\Models\Location;
use Chuckbe\ChuckcmsModuleBooker\Models\Payment;


use Jsvrcek\ICS\Model\Description\Location as ICSLocation;
use Jsvrcek\ICS\Model\CalendarEvent;
use Jsvrcek\ICS\Utility\Formatter;
use Jsvrcek\ICS\CalendarStream;
use Jsvrcek\ICS\Model\Calendar;
use Jsvrcek\ICS\CalendarExport;

use Illuminate\Http\Request;
use ChuckModuleBooker;
use Carbon\Carbon;
use DateInterval;
use ChuckSite;
use DateTime;
use Mollie;
use Mail;
use Auth;
use PDF;

class AppointmentRepository
{
    private $bookerFormRepository;
    private $locationRepository;
    private $serviceRepository;
    private $appointment;

    public function __construct(
        BookerFormRepository $bookerFormRepository,
        LocationRepository $locationRepository,
        ServiceRepository $serviceRepository,
        Appointment $appointment,
        Payment $payment)
    {
        $this->bookerFormRepository = $bookerFormRepository;
        $this->locationRepository = $locationRepository;
        $this->serviceRepository = $serviceRepository;
        $this->appointment = $appointment;
        $this->payment = $payment;
    }

    /**
     * Get all the appointments
     *
     * @return Illuminate\Database\Eloquent\Collection
     **/
    public function get()
    {
        return $this->appointment->get();
    }

    /**
     * Get all the appointments with invoices
     *
     * @return Illuminate\Database\Eloquent\Collection
     **/
    public function getInvoices()
    {
        return $this->appointment->where('has_invoice', true)->get();
    }

    /**
     * Find the service for the given id.
     *
     * @param int $id
     * 
     * @return mixed
     **/
    public function find($id)
    {
        return $this->appointment->where('id', $id)->first();
    }

    /**
     * Create a new appointment.
     *
     * @param Illuminate\Http\Request $request
     * @param $customer
     * 
     * @return mixed
     **/
    public function create(Request $request, $customer)
    {
        $weight = $this->serviceRepository->getWeightForIds($request->services);

        $json = [];
        
        if (array_key_exists('address', $customer->json)) {
            $json['address'] = $customer->json['address'];
        }

        if (array_key_exists('company', $customer->json)) {
            $json['company'] = $customer->json['company'];
        }

        $appointment = $this->appointment->create([
            'location_id' => $request->location,
            'customer_id' => is_null($customer) ? $request->customer : $customer->id,
            'title' => $request->first_name . ' ' . $request->last_name,
            'start' => Carbon::parse($request->date . ' ' . $request->time),
            'end' => Carbon::parse($request->date . ' ' . $request->time)->addMinutes((int)$request->duration),
            'date' => Carbon::parse($request->date)->toDateString(),
            'time' => $request->time,
            'duration' => $request->duration,
            'weight' => $weight,
            'status' => 'awaiting',
            'is_canceled' => false,
            'price' => 0,
            'json' => $json
        ]);

        $appointment->services()->attach($request->services);

        return $appointment;
    }
    
    /**
     * Get all the Appointments for the Location
     *
     * @param Location $location
     * 
     * @return \Illuminate\Support\Collection
     **/
    public function forLocation(Location $location, $sort = 'asc')
    {
        return $this->appointment
                    ->where('location_id', $location->id)
                    ->orderBy('date', $sort)
                    ->orderBy('time', $sort)
                    ->get();
    }

    /**
     * Get all the Appointments for the Location on the given date
     *
     * @param Location $location
     * @param DateTime $date
     * 
     * @return \Illuminate\Support\Collection
     **/
    public function forLocationAndDate(Location $location, DateTime $date, $sort = 'asc')
    {
        return $this->appointment
                    ->where('location_id', $location->id)
                    ->whereDate('date', $date->format('Y-m-d'))
                    ->orderBy('time', $sort)
                    ->get();
    }

    /**
     * Get all the Appointments for the Location and between the given dates
     *
     * @param Location $location
     * @param DateTime $start
     * @param DateTime $end
     * @param string   $sort
     * 
     * @return \Illuminate\Support\Collection
     **/
    public function forLocationAndBetweenDates(Location $location, DateTime $start, DateTime $end, $sort = 'asc')
    {
        return $this->appointment
                    ->where('location_id', $location->id)
                    ->whereDate('date', '>=', $start->format('Y-m-d'))
                    ->whereDate('date', '<=', $end->format('Y-m-d'))
                    ->orderBy('time', $sort)
                    ->get();
    }

    /**
     * Get all the Appointments between the given dates
     *
     * @param DateTime $start
     * @param DateTime $end
     * @param string   $sort
     * 
     * @return \Illuminate\Support\Collection
     **/
    public function betweenDates(DateTime $start, DateTime $end, $sort = 'asc', $select = [])
    {
        $query = $this->appointment
                    ->whereDate('start', '>=', $start->format('Y-m-d'))
                    ->whereDate('end', '<=', $end->format('Y-m-d'))
                    ->orderBy('time', $sort);

        if (count($select) > 0) {
            return $query->select($select)->get();
        }

        return $query->get();
    }

    /**
     * Make an appointment based on the request
     *
     * @param Illuminate\Http\Request $request
     * @param $customer
     * 
     * @return mixed
     **/
    public function makeFromRequest(Request $request, $customer)
    {
        //see if available
        $availability = $this->getAvailabilityForRequest($request);

        if ($availability) {
            return 'unavailable';
        }

        //make actual appointment
        $appointment = $this->create($request, $customer);

        if ($appointment->services->sum('price') > 0) {
            $appointment->price = $appointment->services->sum('price');
            $appointment->update();

            $payment = $this->makePayment($appointment);

            if ($payment === false) {
                $this->updateStatus($appointment, 'confirmed', true);
            }
        } else {
            $this->updateStatus($appointment, 'confirmed', true);
        }

        return $appointment;
        

        //if no payment needed: appointment->status = confirmed
        
        //make payment and associate to appointment
        
        //return appointment
    }

    /**
     * Make a new payment for the appointment
     *
     * @param Chuckbe\ChuckcmsModuleBooker\Models\Appointment $appointment
     * 
     * @return mixed
     **/
    public function makePayment(Appointment $appointment)
    {
        $appointment->refresh();

        $customer = $appointment->customer;
        $otherAppointments = $customer->appointments()->where('json->is_free_session', true)->where('is_canceled', 0)->where('status', 'confirmed')->count();

        if ($otherAppointments == 0) {
            $json = $appointment->json;
            $json['is_free_session'] = true;
            $appointment->json = $json;
            $appointment->price = 0;
            $appointment->update();

            return false;
        }
        
        if ( ($appointment->customer->getAvailableWeight() == -1 || $appointment->customer->getAvailableWeight() >= $appointment->weight) && !in_array($appointment->start->format('Y-m-d'), explode(',', $appointment->customer->getDatesWhenAvailableWeightNotAvailable())) ) {
            $subscription = $appointment->customer->getSubscriptionForWeight($appointment->weight);
            
            if ($subscription->expires_at->format('YmdHi') > $appointment->start->format('YmdHi')) {
                $json = $appointment->json;
                $json['subscription'] = $subscription->id;
                $appointment->json = $json;
                $appointment->save();

                if ($subscription->weight > 0) {
                    $subscription->decrement('weight');
                }

                $subscription->increment('usage');

                return false;
            }
        }

        config(['mollie.key' => ChuckSite::module('chuckcms-module-booker')->getSetting('integrations.mollie.key')]);

        $webhookUrl = route('module.booker.mollie_webhook');
        if (config('app.env') !== 'production') {
            $webhookUrl = 'https://chuckcms.com';
        }

        $mollie = Mollie::api()->payments()->create([
            'amount' => [
                'currency' => 'EUR',
                'value' => number_format( ( (float)$appointment->price ), 2, '.', ''), // You must send the correct number of decimals, thus we enforce the use of strings
            ],
            'method' => 'bancontact',
            'description' => ChuckSite::getSite('name') . ' #' . $appointment->id,
            'webhookUrl' => $webhookUrl,
            'redirectUrl' => route('module.booker.checkout.followup', ['appointment' => $appointment->id]),
            //'method' => $order->json['payment_method'],
            "metadata" => array(
                'price' => number_format( ( (float)$appointment->price ), 2, '.', ''),
                'appointment_id' => $appointment->id
                )
        ], ['include' => 'details.qrCode']);

        $payment = $this->payment->create(['appointment_id' => $appointment->id, 'external_id' => $mollie->id, 'type' => 'one-off', 'status' => 'awaiting', 'amount' => $appointment->price, 'log' => array(), 'json' => array()]);

        $mollie = Mollie::api()->payments()->get($mollie->id);

        return $mollie;
    }

    /**
     * Get the QR code for the appointment
     *
     * @param Chuckbe\ChuckcmsModuleBooker\Models\Appointment $appointment
     * 
     * @return mixed
     **/
    public function getQrCode(Appointment $appointment)
    {
        if ($appointment->is_paid) {
            return false;
        }

        config(['mollie.key' => ChuckSite::module('chuckcms-module-booker')->getSetting('integrations.mollie.key')]);

        $payment = $appointment->payments->first();

        $mollie = Mollie::api()->payments()->get($payment->external_id, ['include' => 'details.qrCode']);

        if (is_null($mollie->details)) {
            return false;
        }

        return $mollie->details->qrCode->src;
    }

    /**
     * Get all the availability for the given request
     *
     * @param Illuminate\Http\Request $request
     * 
     * @return bool
     **/
    public function getAvailabilityForRequest(Request $request)
    {
        $from = Carbon::parse($request->date);
        $availability = $this->getAvailableDates($request->location, $request->services, $from, 1);

        if ($availability == false) {
            return $availability;
        }

        if (array_key_exists($from->format('Ymd'), $availability)) {
            $key = $from->format('Ymd');

            foreach($availability[$key]['timeslots'] as $timeslot) {
                if ($timeslot['start'] == $request->time) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Get all the available dates for the given location and selected services
     *
     * @param string        $locationId
     * @param array         $servicesIds
     * @param Carbon\Carbon $from
     * @param int           $futureDays
     * 
     * @return mixed
     **/
    public function getAvailableDates(string $locationId, array $servicesIds, $from = null, int $futureDays = 60) 
    {
        $location = $this->locationRepository->find($locationId);
        $services = $this->serviceRepository->find($servicesIds);

        $duration = $services->sum('duration');
        $weight = $services->sum('weight');

        if ($location->max_weight < $weight) {
            return false;
        }

        if (is_null($from)) {
            $now = now()->toDateString();
            $futureUntil = now()->addDays($futureDays)->toDateString();
        } else {
            $now = $from->toDateString();
            $futureUntil = $from->addDays($futureDays)->toDateString();
        }
        
        $futureDates = $this->bookerFormRepository->getDatesBetween($now, $futureUntil);
        $appointments = $this->forLocationAndBetweenDates($location, new DateTime($now), new DateTime($futureUntil))->where('is_canceled', 0)->where('status', '!=', 'awaiting')->where('status', '!=', 'canceled')->where('status', '!=', 'failed');

        $availableDates = [];
        $availableDatesCount = 0;

        foreach ($futureDates as $date) {
            $key = $date->format('Ymd');

            if (!$this->locationRepository->isDateAvailable($location, $date)) {
                $availableDates[$key] = $this->formatDisabledDate($date);
                continue;
            }

            $availableDates[$key] = $this->formatDate($date, $location, $duration, $weight, $appointments);
            
            if ($availableDates[$key]['status'] == 'available') {
                $availableDatesCount++;
            }
        }

        if ($availableDatesCount == 0) {
            return false;
        }

        return $availableDates;
    }

    /**
     * Get the available timeslots for a given date, location and duration of time needed.
     *
     * @param DateTime $date
     * @param Location $location
     * @param int $duration
     * @param int $weight
     * @param \Illuminate\Support\Collection $appointments
     * 
     * @return array
     **/
    public function getAvailableTimeslots(DateTime $date, Location $location, int $duration, int $weight, $appointments)
    {
        $openingHoursSections = $location->getOpeningHoursSectionsForDay(strtolower($date->format('l')));
        
        $appointments = $appointments->filter(function ($item) use ($date) {
            return $item->date->format('Y-m-d') == $date->format('Y-m-d');
        })->sortBy('time');
        
        $maxWeight = $location->max_weight;
        $interval = $location->interval; //@IDEA: $duration = $duration + $minutesBefore + $minutesAfter;
        $timeslots = [];

        foreach($openingHoursSections as $openingHourSection) {
            if (now()->format('Ymd') == $date->format('Ymd')) {
                $oHSEnd = new DateTime($openingHourSection['end']);
                if (now()->format('Hi') >= $oHSEnd->format('Hi')) {
                    continue;
                }
            }

            $sectionStart = $openingHourSection['start'];
            $oHSStart = new DateTime($openingHourSection['start']);

            if (now()->format('Ymd') == $date->format('Ymd')) {
                if (now()->format('Hi') >= $oHSStart->format('Hi')) {
                    $sectionStart = now()->ceilUnit('minute', 15)->format('H:i');
                }
            } 

            
            $sectionEnd = $openingHourSection['end'];

            $sectionStartDT = new DateTime($sectionStart);
            $sectionEndDT = new DateTime($sectionEnd);

            $sectionAppointments = $appointments
                                        ->where('time', '>=', $sectionStart)
                                        ->where('time', '<=', $sectionEnd);

            if (count($sectionAppointments) == 0) {
                $timeslots = array_merge(
                                $timeslots, 
                                $this->getTimeslotsBetween($sectionStart, $sectionEnd, $duration, $interval)
                            );
                continue;
            }

            $unfilteredSlots = $this->bookerFormRepository
                                        ->getPeriodBetween(
                                            $sectionStart, 
                                            $sectionEnd, 
                                            $interval.' minutes'
                                        );

            foreach ($unfilteredSlots as $unfilteredSlot) {
                $slotStart = $unfilteredSlot->format('H:i'); //DateTime
                $slotEnd = $unfilteredSlot->add(DateInterval::createFromDateString($duration.' minutes'))->format('H:i');

                if ($unfilteredSlot->format('Hi') > $sectionEndDT->format('Hi')) {
                    continue;
                }

                $slotAppointments = $sectionAppointments
                                            ->where('time', '>=', $slotStart)
                                            ->where('time', '<', $slotEnd);

                $timeslot = [];
                $timeslot['start'] = $slotStart;

                if (count($slotAppointments) == 0) {
                    $timeslot['end'] = $slotEnd;
                    $timeslots[] = $timeslot;
                    continue;
                }

                $highestWeightUsedAtOneTime = $this->highestWeight($slotAppointments);

                if ($weight <= ($maxWeight - $highestWeightUsedAtOneTime)) {
                    $timeslot['end'] = $slotEnd;
                    $timeslots[] = $timeslot;
                    continue;
                }
            }

        }

        return $timeslots;
    }

    /**
     * Check the highest weight at one point in time of the given appointments
     *
     * @param $appointments
     * 
     * @return int
     **/
    public function highestWeight($appointments)
    {
        $earliest = $appointments->sortBy('time')->first()->start;
        $latest = $appointments->sortByDesc('end')->first()->end;

        $period = $this->bookerFormRepository->getPeriodBetween($earliest, $latest, '1 minute');

        $weights = [];
        foreach ($period as $minute) {
            $weights[] = $appointments->filter(function ($item) use ($minute) {
                                return $item->start->format('YmdHi') <= $minute->format('YmdHi') && $item->end->format('YmdHi') >= $minute->format('YmdHi');
                            })->sum('weight');
        }

        return max($weights);
    }

    /**
     * Get all the timeslots between a start an end for a given duration
     *
     * @param $start
     * @param $end
     * @param int $duration
     * @param int $interval
     * 
     * @return array
     **/
    public function getTimeslotsBetween($start, $end, $duration, $interval)
    {
        $timeslots = [];

        $startHour = new DateTime($start);
        $endHour = new DateTime($end);
        $minutesBetween = $this->getIntervalTotalMinutes($startHour->diff(new DateTime($end)));
        
        if ($minutesBetween >= $duration) {
            $availablePeriod = $this->bookerFormRepository->getPeriodBetween($start, $end, $interval > 1 ? $interval.' minutes' : $interval.' minute');
            
            foreach ($availablePeriod as $timeslotStart) {
                $timeslot = [];
                $timeslot['start'] = $timeslotStart->format('H:i');

                $timeslotEnd = $timeslotStart->add(DateInterval::createFromDateString($duration.' minutes'));

                if ($timeslotEnd->getTimestamp() <= $endHour->getTimestamp()) {
                    $timeslot['end'] = $timeslotEnd->format('H:i');
                    $timeslots[] = $timeslot;
                }
            }
        }

        return $timeslots;
    }

    /**
     * Format an (un)available date
     *
     * @param DateTime $date
     * @param Location $location
     * @param int $duration
     * @param int $weight
     * @param \Illuminate\Support\Collection $appointments
     * 
     * @return array
     **/
    public function formatDate(DateTime $date, Location $location, int $duration, int $weight, $appointments)
    {
        $array = [];

        
        $array['date'] = $date->format('d/m/Y');
        $array['short_weekday'] = $date->format('D');
        $array['day'] = $date->format('d');
        $array['month'] = $date->format('m');
        $array['short_month'] = $date->format('M');
        $array['year'] = $date->format('Y');
        $array['timeslots'] = $this->getAvailableTimeslots($date, $location, $duration, $weight, $appointments);
        $array['status'] = count($array['timeslots']) > 0 ? 'available' : 'unavailable';

        return $array;
    }

    /**
     * Format a disabled date
     *
     * @param DateTime $date
     * 
     * @return array
     **/
    public function formatDisabledDate(DateTime $date)
    {
        $array = [];

        $array['status'] = 'disabled';
        $array['date'] = $date->format('d/m/Y');
        $array['short_weekday'] = $date->format('D');
        $array['day'] = $date->format('d');
        $array['month'] = $date->format('m');
        $array['short_month'] = $date->format('M');
        $array['year'] = $date->format('Y');
        $array['timeslots'] = [];

        return $array;
    }

    /**
     * Format total minutes from a date interval
     *
     * @param DateInterval $int
     * 
     * @return int
     **/
    public function getIntervalTotalMinutes(DateInterval $int)
    {
        return ($int->days * 24 * 60) + ($int->h * 60) + $int->i;
    }

    public function updateStatus(Appointment $appointment, $status, $sendEmail = false)
    {
        $status_object = ChuckModuleBooker::getSetting('appointment.statuses.'.$status);
        $appointment->status = $status;
        $json = is_null($appointment->json) ? [] : $appointment->json; 
        
        if($status_object['invoice'] && !array_key_exists('invoice_number', $json) && !array_key_exists('subscription', $json) && !array_key_exists('is_free_session', $json)) {
            $json['invoice_number'] = $this->generateInvoiceNumber();
            $appointment->json = $json;
            $appointment->has_invoice = true;
        }

        if ($status == 'canceled') {
            $appointment->is_canceled = true;
        }

        $appointment->update();

        if($status_object['send_email'] && $sendEmail) {
            if($status_object['invoice'] && $appointment->has_invoice) {
                $pdf = $this->generatePDF($appointment);
            } else {
                $pdf = null;
            }

            foreach($status_object['email'] as $emailKey => $email) {
                $this->sendMail($appointment, $status_object, $emailKey, $email, $pdf);
                sleep(1);
            }
        }
    }

    private function sendMail(Appointment $appointment, array $status, string $emailKey, array $email, $pdf = null)
    {
        $invoice = $appointment->has_invoice;
        $template = $email['template'];
        $to = $this->replaceEmailVariables($appointment, is_null($email['to']) ? '' : $email['to']);
        $to_name = $this->replaceEmailVariables($appointment, is_null($email['to_name']) ? '' : $email['to_name']);
        $cc = $this->replaceEmailVariables($appointment, is_null($email['cc']) ? '' : $email['cc']);
        $bcc = $this->replaceEmailVariables($appointment, is_null($email['bcc']) ? '' : $email['bcc']);

        if(array_key_exists('ics', $email) && $email['ics']) {
            $ics = $this->generateICS($appointment);
        } else {
            $ics = null;
        }

        $data = $this->prepareEmailData($appointment, $email);

        Mail::send($template, ['appointment' => $appointment, 'email' => $email, 'data' => $data], function ($m) use ($appointment, $status, $email, $to, $to_name, $cc, $bcc, $data, $invoice, $pdf, $ics) {
            $m->from(config('chuckcms-module-booker.emails.from_email'), config('chuckcms-module-booker.emails.from_name'));
            
            $m->to($to, $to_name)->subject($data['subject']);

            if( $cc !== false && $cc !== null && $cc !== ''){
                $m->cc($cc);
            }

            if( $bcc !== false && $bcc !== null && $bcc !== ''){
                $m->bcc($bcc);
            }

            if ($invoice) {
                $m->attachData($pdf, $appointment->invoiceFileName, ['mime' => 'application/pdf']);
            }

            if ($ics !== null) {
                $m->attachData($ics->getStream(), ChuckSite::getSite('name') . "_appointment" . ".ics");
            }
        });    
    }

    private function prepareEmailData(Appointment $appointment, array $email)
    {
        $data = [];
        $locale = app()->getLocale();

        foreach($email['data'] as $emailDataKey => $emailData) {
            $data[$emailDataKey] = $this->replaceEmailVariables($appointment, $emailData['value'][$locale]);
        }

        return $data;
    }

    private function replaceEmailVariables(Appointment $appointment, string $value)
    {
        $foundVariables = $this->getRawVariables($value, '[%', '%]');
        if (count($foundVariables) > 0) {
            foreach ($foundVariables as $foundVariable) {
                if (strpos($foundVariable, 'APPOINTMENT_NUMBER') !== false) {
                    $value = str_replace('[%APPOINTMENT_NUMBER%]', $appointment->start->format('Ymd').'-'.$appointment->id, $value);
                }
                // if (strpos($foundVariable, 'APPOINTMENT_SUBTOTAL') !== false) {
                //     $value = str_replace('[%APPOINTMENT_SUBTOTAL%]', ChuckEcommerce::formatPrice($order->subtotal), $value);
                // }
                // if (strpos($foundVariable, 'APPOINTMENT_SUBTOTAL_TAX') !== false) {
                //     $value = str_replace('[%APPOINTMENT_SUBTOTAL_TAX%]', ChuckEcommerce::formatPrice($order->subtotal_tax), $value);
                // }
                // if (strpos($foundVariable, 'APPOINTMENT_SHIPPING') !== false) {
                //     $value = str_replace('[%APPOINTMENT_SHIPPING%]', ChuckEcommerce::formatPrice($order->shipping), $value);
                // }
                // if (strpos($foundVariable, 'APPOINTMENT_SHIPPING_TAX') !== false) {
                //     $value = str_replace('[%APPOINTMENT_SHIPPING_TAX%]', ChuckEcommerce::formatPrice($order->shipping_tax), $value);
                // }
                // if (strpos($foundVariable, 'APPOINTMENT_SHIPPING_TOTAL') !== false) {
                //     $value = str_replace('[%APPOINTMENT_SHIPPING_TOTAL%]', $order->shipping > 0 ? ChuckEcommerce::formatPrice($order->shipping + $order->shipping_tax) : 'gratis', $value);
                // }
                if (strpos($foundVariable, 'APPOINTMENT_TOTAL') !== false) {
                    $value = str_replace('[%APPOINTMENT_TOTAL%]', ChuckModuleBooker::formatPrice($appointment->price), $value);
                }
                // if (strpos($foundVariable, 'APPOINTMENT_TOTAL_TAX') !== false) {
                //     $value = str_replace('[%APPOINTMENT_TOTAL_TAX%]', ChuckEcommerce::formatPrice($order->total_tax), $value);
                // }
                // if (strpos($foundVariable, 'APPOINTMENT_FINAL') !== false) {
                //     $value = str_replace('[%APPOINTMENT_FINAL%]', ChuckEcommerce::formatPrice($order->final), $value);
                // }
                // if (strpos($foundVariable, 'APPOINTMENT_PAYMENT_METHOD') !== false) {
                //     $value = str_replace('[%APPOINTMENT_PAYMENT_METHOD%]', $order->json['payment_method'], $value);
                // }
                


                if (strpos($foundVariable, 'APPOINTMENT_FIRST_NAME') !== false) {
                    $value = str_replace('[%APPOINTMENT_FIRST_NAME%]', $appointment->customer->first_name, $value);
                }
                if (strpos($foundVariable, 'APPOINTMENT_LAST_NAME') !== false) {
                    $value = str_replace('[%APPOINTMENT_LAST_NAME%]', $appointment->customer->last_name, $value);
                }
                if (strpos($foundVariable, 'APPOINTMENT_EMAIL') !== false) {
                    $value = str_replace('[%APPOINTMENT_EMAIL%]', $appointment->customer->email, $value);
                }
                if (strpos($foundVariable, 'APPOINTMENT_TELEPHONE') !== false) {
                    $value = str_replace('[%APPOINTMENT_TELEPHONE%]', !is_null($appointment->customer->tel) ? $appointment->customer->tel : '', $value);
                }



                // if (strpos($foundVariable, 'APPOINTMENT_COMPANY') !== false) {
                //     $value = str_replace('[%APPOINTMENT_COMPANY%]', !is_null($order->json['company']['name']) ? $order->json['company']['name'] : '', $value);
                // }
                // if (strpos($foundVariable, 'APPOINTMENT_COMPANY_VAT') !== false) {
                //     $value = str_replace('[%APPOINTMENT_COMPANY_VAT%]', !is_null($order->json['company']['name']) ? $order->json['company']['vat'] : '', $value);
                // }
                // if (strpos($foundVariable, 'APPOINTMENT_BILLING_STREET') !== false) {
                //     $value = str_replace('[%APPOINTMENT_BILLING_STREET%]', $order->json['address']['billing']['street'], $value);
                // }
                // if (strpos($foundVariable, 'APPOINTMENT_BILLING_HOUSENUMBER') !== false) {
                //     $value = str_replace('[%APPOINTMENT_BILLING_HOUSENUMBER%]', $order->json['address']['billing']['housenumber'], $value);
                // }
                // if (strpos($foundVariable, 'APPOINTMENT_BILLING_POSTALCODE') !== false) {
                //     $value = str_replace('[%APPOINTMENT_BILLING_POSTALCODE%]', $order->json['address']['billing']['postalcode'], $value);
                // }
                // if (strpos($foundVariable, 'APPOINTMENT_BILLING_CITY') !== false) {
                //     $value = str_replace('[%APPOINTMENT_BILLING_CITY%]', $order->json['address']['billing']['city'], $value);
                // }
                // if (strpos($foundVariable, 'APPOINTMENT_BILLING_COUNTRY') !== false) {
                //     $value = str_replace('[%APPOINTMENT_BILLING_COUNTRY%]', config('chuckcms-module-ecommerce.countries_data.'.$order->json['address']['billing']['country'].'.native'), $value);
                // }



                if (strpos($foundVariable, 'APPOINTMENT_PAYMENT_URL') !== false) {
                    $value = str_replace('[%APPOINTMENT_PAYMENT_URL%]', route('module.booker.checkout.retry_payment', ['appointment' => $appointment->id]), $value);
                }

                if (strpos($foundVariable, 'APPOINTMENT_SERVICES') !== false) {
                    $value = str_replace('[%APPOINTMENT_SERVICES%]', $this->formatServices($appointment), $value);
                }
            }
        }
        return $value;
    }

    private function formatServices(Appointment $appointment) {
        $string = '';

        $string .= '<p><b>Datum: </b>'.$appointment->start->format('d/m/Y').'<br>';
        $string .= '<b>Uur: </b>'.$appointment->time.'<br>';
        $string .= '<b>Locatie: </b>'.$appointment->location->long_address;
        $string .= '</p>';
        
        foreach($appointment->services as $service) {
            if (!array_key_exists('is_free_session', $appointment->json)) {
                $string .= '<p>1x "'.$service->name.'" ('.ChuckModuleBooker::formatPrice($service->price).')</p><hr>';
            } else {
                $string .= '<p>1x "'.$service->name.'" (gratis eerste sessie)</p><hr>';
            }
        }

        return $string;
    }

    private function getRawVariables($str, $startDelimiter, $endDelimiter) 
    {
        $contents = array();
        $startDelimiterLength = strlen($startDelimiter);
        $endDelimiterLength = strlen($endDelimiter);
        $startFrom = 0;
        while (false !== ($contentStart = strpos($str, $startDelimiter, $startFrom))) {
            $contentStart += $startDelimiterLength;
            $contentEnd = strpos($str, $endDelimiter, $contentStart);
            if (false === $contentEnd) {
                break;
            }
            $contents[] = substr($str, $contentStart, $contentEnd - $contentStart);
            $startFrom = $contentEnd + $endDelimiterLength;
        }

        return $contents;
    }

    private function generatePDF(Appointment $appointment)
    {
        $pdf = PDF::loadView('chuckcms-module-booker::pdf.invoice', compact('appointment'));
        return $pdf->output();
    }

    public function downloadInvoice(Appointment $appointment)
    {
        $pdf = PDF::loadView('chuckcms-module-booker::pdf.invoice', compact('appointment'));
        return $pdf->download($appointment->invoiceFileName);
    }

    private function generateInvoiceNumber()
    {
        $invoice_number = ChuckModuleBooker::getSetting('invoice.number') + 1;
        ChuckModuleBooker::setSetting('invoice.number', $invoice_number);
        return $invoice_number;
    }

    private function generateICS(Appointment $appointment)
    {
        $location = new ICSLocation;
        $location->setName($appointment->location->name);

        $icsEvent = new CalendarEvent();
        $icsEvent->setStart(\Carbon\Carbon::parse($appointment->date->format("Y-m-d") . " " . $appointment->time)->toDateTime())
            ->setEnd(\Carbon\Carbon::parse($appointment->date->format("Y-m-d") . " " . $appointment->time)->addMinutes($appointment->duration)->toDateTime())
            ->setSummary('')
            ->setLocations([$location])
            ->setStatus("CONFIRMED")
            ->setUid('cmb' . $appointment->id);

        $calendar = new Calendar();
        $calendar->addEvent($icsEvent);
        $calendar->setProdId("//" . ChuckSite::getSite('name') . "//Appointments//NL");
        $calendar->setTimezone(new \DateTimeZone("Europe/Brussels"));

        $calendarExport = new CalendarExport(new CalendarStream, new Formatter());
        $calendarExport->addCalendar($calendar);

        return $calendarExport;
    }
}