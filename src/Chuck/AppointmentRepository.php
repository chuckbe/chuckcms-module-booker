<?php

namespace Chuckbe\ChuckcmsModuleBooker\Chuck;

use Chuckbe\ChuckcmsModuleBooker\Models\Appointment;
use Chuckbe\ChuckcmsModuleBooker\Models\Location;
use Illuminate\Http\Request;
use Auth;

class AppointmentRepository
{
    private $appointment;

    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    /**
     * Get all the locations
     *
     * @var string
     **/
    public function get()
    {
        return $this->appointment->get();
    }
    /**
     * Get all the Appointments for the Location
     *
     * @param Location $location
     * 
     * @return \Illuminate\Support\Collection
     **/
    public function forLocation(Location $location)
    {
        return $this->appointment->where('location_id', $location->id)->get();
    }
}