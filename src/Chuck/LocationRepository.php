<?php

namespace Chuckbe\ChuckcmsModuleBooker\Chuck;

use Chuckbe\ChuckcmsModuleBooker\Models\Location;


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
     * @return Illuminate\Database\Eloquent\Collection
     **/
    public function get()
    {
        return $this->location->get();
    }

    /**
     * Find the location for the given id.
     *
     * @param int $id
     * 
     * @return mixed
     **/
    public function find($id)
    {
        return $this->location->where('id', $id)->first();
    }

    /**
     * Delete the given location.
     *
     * @param Location $location
     * 
     * @return bool
     **/
    public function delete(Location $location)
    {
        return $location->delete();
    }
}