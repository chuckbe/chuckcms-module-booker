<?php

namespace Chuckbe\ChuckcmsModuleBooker\Chuck;

use Chuckbe\ChuckcmsModuleBooker\Requests\StoreLocationRequest;
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
        return $this->location->with('services')->get();
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
     * Create or update a location.
     *
     * @param StoreLocationRequest $request
     * 
     * @return mixed
     **/
    public function createOrUpdate(StoreLocationRequest $request)
    {
        if ($request->has('id') && $request->has('update')) {
            return $this->update($request);
        }

        return $this->create($request);
    }

    /**
     * Create a new location.
     *
     * @param StoreLocationRequest $request
     * 
     * @return Chuckbe\ChuckcmsModuleBooker\Models\Location
     **/
    public function create(StoreLocationRequest $request)
    {
        $opening_hours = $this->formatOpeningHours($request);

        $this->location->create([
            'name' => $request->get('name'),
            'disabled_weekdays' => $request->get('disabled_weekdays'),
            'disabled_dates' => explode(',', $request->get('disabled_dates')),
            'opening_hours' => $opening_hours,
            'order' => (int)$request->get('order'),
            'json' => array()
        ]);

        $location->refresh();

        if (is_array($request->get('services'))) {
            $location->services()->attach($request->get('services'));   
        }

        return $location;
    }

    /**
     * Create a new location.
     *
     * @param StoreLocationRequest $request
     * 
     * @return Chuckbe\ChuckcmsModuleBooker\Models\Location
     **/
    public function update(StoreLocationRequest $request)
    {
        //dd($request->get('disabled_weekdays'));
        $opening_hours = $this->formatOpeningHours($request);
//dd($request->get('name'));
        $location = $this->location->where('id', $request->get('id'))->first();

        $location->update([
            'name' => $request->get('name'),
            'disabled_weekdays' => $request->get('disabled_weekdays'),
            'disabled_dates' => explode(',', $request->get('disabled_dates')),
            'opening_hours' => $opening_hours,
            'order' => (int)$request->get('order'),
            'json' => array()
        ]);

        $location->refresh();

        if (is_array($request->get('services'))) {
            $location->services()->sync($request->get('services'));
        }

        return $location;
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

    /**
     * Format the opening hours from a request.
     *
     * @param Location $location
     * 
     * @return array
     **/
    public function formatOpeningHours(StoreLocationRequest $request)
    {
        $opening_hours = array();

        $disabled_weekdays = $request->get('disabled_weekdays');

        $opening_hours['monday']    = $this->formatOpeningHoursForDay($request, 'monday');
        $opening_hours['tuesday']   = $this->formatOpeningHoursForDay($request, 'tuesday');
        $opening_hours['wednesday'] = $this->formatOpeningHoursForDay($request, 'wednesday');
        $opening_hours['thursday']  = $this->formatOpeningHoursForDay($request, 'thursday');
        $opening_hours['friday']    = $this->formatOpeningHoursForDay($request, 'friday');
        $opening_hours['saturday']  = $this->formatOpeningHoursForDay($request, 'saturday');
        $opening_hours['sunday']   = $this->formatOpeningHoursForDay($request, 'sunday');

        return $opening_hours;
    }

    /**
     * Format the opening hours for one day from a request.
     *
     * @param Location $location
     * @param string   $day
     * 
     * @return array
     **/
    public function formatOpeningHoursForDay(StoreLocationRequest $request, $day)
    {
        $day = strtolower($day);
        $opening_hours = array();

        if (!$request->has('start_time_'.$day)) {
            return array();
        }
        
        foreach ($request->get('start_time_'.$day) as $key => $value) {
            $hours = array();
            $hours['start'] = $request->get('start_time_'.$day)[$key];
            $hours['end'] = $request->get('end_time_'.$day)[$key];
            $opening_hours[] = $hours;
        }

        return $opening_hours;
    }
}