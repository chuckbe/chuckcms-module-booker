<?php

namespace Chuckbe\ChuckcmsModuleBooker\Chuck;

use Chuckbe\ChuckcmsModuleBooker\Models\Appointment;
use Chuckbe\ChuckcmsModuleBooker\Models\Location;
use Chuckbe\ChuckcmsModuleBooker\Models\Service;
use Chuckbe\ChuckcmsModuleBooker\Models\Payment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookerFormRepository
{
    private $appointment;

    public function __construct(Appointment $appointment, Service $service)
    {
        $this->appointment = $appointment;
        $this->service = $service;
    }

    /**
     * Get all the locations
     *
     * @var string
     **/
    public function getServices()
    {
        return $this->service->get();
    }

    public function render()
    {
        $services = $this->getServices();
    	return view('chuckcms-module-booker::frontend.form', compact('services'))->render();
    }

    public function styles()
    {
        return view('chuckcms-module-booker::frontend.css')->render();
    }

    public function scripts()
    {
        return view('chuckcms-module-booker::frontend.scripts')->render();
    }
    
}