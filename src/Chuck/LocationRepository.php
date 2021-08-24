<?php

namespace Chuckbe\ChuckcmsModuleBooker\Chuck;

use Chuckbe\ChuckcmsModuleBooker\Models\Location;
use Illuminate\Http\Request;
use Auth;

class LocationRepository
{
    private $location;

    public function __construct(Location $location)
    {
        $this->location = $location;
    }

    /**
     * Get all the locations
     *
     * @var string
     **/
    public function get()
    {
        return $this->location->get();
    }
}