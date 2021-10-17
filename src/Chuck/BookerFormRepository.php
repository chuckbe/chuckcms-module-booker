<?php

namespace Chuckbe\ChuckcmsModuleBooker\Chuck;

use Chuckbe\ChuckcmsModuleBooker\Chuck\SubscriptionPlanRepository;
use Chuckbe\ChuckcmsModuleBooker\Chuck\LocationRepository;
use Chuckbe\ChuckcmsModuleBooker\Chuck\ServiceRepository;
use Chuckbe\ChuckcmsModuleBooker\Models\Location;
use Chuckbe\ChuckcmsModuleBooker\Models\Payment;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use DateTime;

class BookerFormRepository
{
    private $subscriptionPlanRepository;
    private $locationRepository;
    private $appointment;

    public function __construct(
        SubscriptionPlanRepository $subscriptionPlanRepository, 
        LocationRepository $locationRepository, 
        ServiceRepository $serviceRepository)
    {
        $this->subscriptionPlanRepository = $subscriptionPlanRepository;
        $this->locationRepository = $locationRepository;
        $this->serviceRepository = $serviceRepository;
    }

    /**
     * Return form in frontend.
     *
     * @return Illuminate/View/View
     */
    public function render()
    {
        $locations = $this->locationRepository->get();
        if (count($locations) == 1) {
            $services = $locations->first()->services()->get();
        } else {
            $services = $this->serviceRepository->get();
        }

    	return view('chuckcms-module-booker::frontend.form', compact('locations', 'services'))->render();
    }

    /**
     * Return styles in frontend.
     *
     * @return Illuminate/View/View
     */
    public function styles()
    {
        return view('chuckcms-module-booker::frontend.css')->render();
    }

    /**
     * Return scripts in frontend.
     *
     * @return Illuminate/View/View
     */
    public function scripts()
    {
        return view('chuckcms-module-booker::frontend.scripts')->render();
    }

    /**
     * Return subscription form in frontend.
     *
     * @return Illuminate/View/View
     */
    public function subscriptionRender()
    {
        $locations = $this->locationRepository->get();
        $subscription_plans = $this->subscriptionPlanRepository->get();

        return view('chuckcms-module-booker::frontend.subscription.form', compact('locations', 'subscription_plans'))->render();
    }

    /**
     * Return subscription styles in frontend.
     *
     * @return Illuminate/View/View
     */
    public function subscriptionStyles()
    {
        return view('chuckcms-module-booker::frontend.subscription.css')->render();
    }

    /**
     * Return subscription scripts in frontend.
     *
     * @return Illuminate/View/View
     */
    public function subscriptionScripts()
    {
        return view('chuckcms-module-booker::frontend.subscription.scripts')->render();
    }

    /**
     * Get all dates between two dates.
     *
     * @return \DatePeriod
     */
    public function getDatesBetween($start, $end)
    {
        return $this->getPeriodBetween($start, $end, '1 day');
    }

    /**
     * Get period between two datetimes by given interval.
     *
     * @param $start
     * @param $end
     * @param $interval
     *
     * @return DatePeriod
     */
    public function getPeriodBetween($start, $end, $interval)
    {
        $startDate = new DateTime($start);
        $endDate = new DateTime($end);

        $interval = DateInterval::createFromDateString($interval);
        $period = new DatePeriod($startDate, $interval, $endDate);

        return $period;
    }
    
}