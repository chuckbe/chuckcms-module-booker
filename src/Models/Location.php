<?php

namespace Chuckbe\ChuckcmsModuleBooker\Models;

use Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Eloquent
{
    use SoftDeletes;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cmb_locations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order', 'name', 'disabled_weekdays', 'disabled_dates', 'lat', 'long', 'opening_hours', 'json', 'google_calendar_id'
    ];

    protected $casts = [
        'disabled_weekdays' => 'array',
        'disabled_dates' => 'array',
        'opening_hours' => 'array',
        'json' => 'array',
    ];

    /**
    * A location may have many appointments.
    *
    * @var array
    */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    /**
    * The services that belong to the location.
    *
    * @var array
    */
    public function services()
    {
        return $this->belongsToMany(Service::class, 'locations_services', 'location_id', 'service_id');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @param  string $day
     * @return bool
     */
    public function isDisabledOnDay(string $day)
    {
        if (!is_array($this->disabled_weekdays)) {
            return false;
        }
        
        switch ($day) {
            case 'monday':
                return in_array('1', $this->disabled_weekdays);

            case 'tuesday':
                return in_array('2', $this->disabled_weekdays);

            case 'wednesday':
                return in_array('3', $this->disabled_weekdays);

            case 'thursday':
                return in_array('4', $this->disabled_weekdays);

            case 'friday':
                return in_array('5', $this->disabled_weekdays);

            case 'saturday':
                return in_array('6', $this->disabled_weekdays);

            case 'sunday':
                return in_array('0', $this->disabled_weekdays);
            
            default:
                return true;
        }
    }

    /**
     * The attributes that are mass assignable.
     *
     * @param  int $day
     * @return bool
     */
    public function isDisabledOnWeekday(int $day)
    {
        return is_array($this->disabled_weekdays) && in_array($day, $this->disabled_weekdays);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @param  string $day
     * @return array
     */
    public function getOpeningHoursSectionsForDay(string $day)
    {
        if ($this->isDisabledOnDay($day)) {
            return array();
        }

        return $this->opening_hours[$day];
    }
}
