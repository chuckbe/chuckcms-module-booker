<?php

namespace Chuckbe\ChuckcmsModuleBooker\Models;

use Eloquent;
use ChuckSite;
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
        'order', 'name', 'disabled_weekdays', 'disabled_dates', 'lat', 'long', 'opening_hours', 'max_weight', 'interval', 'json', 'google_calendar_id'
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

    /**
     * The attributes that are mass assignable.
     *
     * @param  \DateTime $date
     * @return array
     */
    public function getOpeningHoursSectionsForDate(\DateTime $date)
    {
        if ($this->isDisabledOnDay(strtolower($date->format('l')))) {
            return array();
        }

        if ($this->hasDisabledSegmentsOn($date)) {
            return $this->combinedOpeningHoursAndDisabledSegments($date);
        }

        return $this->opening_hours[strtolower($date->format('l'))];
    }

    public function hasDisabledSegmentsOn(\DateTime $date)
    {
        foreach ($this->disabled_dates as $key => $disabled_date) {
            if (!is_array($disabled_date)) {
                return false;
            }

            if ($disabled_date['date'] == $date->format('Y-m-d') && !$disabled_date['full_day']) {
                return true;
            }
        }

        return false;
    }

    private function combinedOpeningHoursAndDisabledSegments(\DateTime $date)
    {
        $opening_hours = $this->opening_hours[strtolower($date->format('l'))];
        $disabled_segments = $this->getDisabledSegments($date);

        $segments = array();

        foreach ($opening_hours as $ohKey => $opening_hour) {
            $disabled_segments_filtered = array_filter($disabled_segments, function ($item) use ($opening_hour) {
                return $opening_hour['start'] < $item['end'] && $opening_hour['end'] > $item['start'];
            });
            foreach ($disabled_segments_filtered as $key => $disabled_segment) {
                
                if ($key == 0) {
                    $segments[] = array(
                        'start' => $opening_hour['start'],
                        'end'   => $disabled_segment['start']
                    );

                    if ((int)$opening_hour['start'] > (int)$disabled_segment['start'] && count($disabled_segments) > 2) {
                        $segments[] = array(
                            'start' => $disabled_segment['end'],
                            'end'   => $disabled_segments[$key + 1]['start']
                        );
                    }

                    if (count($disabled_segments) == 1) {
                        $segments[] = array(
                            'start' => $disabled_segment['end'],
                            'end'   => $opening_hour['end']
                        );
                    }
                }

                if ($key > 0 && $key < (count($disabled_segments) - 1)) {
                    $segments[] = array(
                        'start' => $disabled_segment['end'],
                        'end'   => $disabled_segments[$key + 1]['start']
                    );
                }

                if ($key > 0 && $key == (count($disabled_segments) - 1)) {
                    if (count($disabled_segments) == 2) {
                        $segments[] = array(
                            'start' => $disabled_segments[$key - 1]['end'],
                            'end'   => $disabled_segment['start']
                        );
                    }
                    
                    $segments[] = array(
                        'start' => $disabled_segment['end'],
                        'end'   => $opening_hour['end']
                    );
                }
            }
        }
        
        return $segments;
    }

    public function getLongAddressAttribute()
    {
        if (!is_array($this->json)) {
            return ChuckSite::getSetting('company.name').' - '.ChuckSite::getSetting('company.street').' '.ChuckSite::getSetting('company.housenumber').', '.ChuckSite::getSetting('company.postalcode').' '.ChuckSite::getSetting('company.city');
        }
        
        return array_key_exists('address', $this->json) ? $this->json['address'] : ChuckSite::getSetting('company.name').' - '.ChuckSite::getSetting('company.street').' '.ChuckSite::getSetting('company.housenumber').', '.ChuckSite::getSetting('company.postalcode').' '.ChuckSite::getSetting('company.city');
    }

    public function getDisabledSegments(\DateTime $date)
    {
        $date = $date->format('Y-m-d');
        
        $segments = array_map(function ($a) use ($date) {
                if (!$a['full_day'] && $a['date'] == $date) {
                    return array(
                        'start' => $a['start'],
                        'end'   => $a['end']
                    );
                }
            }, $this->disabled_dates);

        $segments = array_filter($segments);
        
        $sort = array_column($segments, 'start');
        $multi = array_multisort($sort, SORT_ASC, $segments);
        return $segments;
    }

    /**
     * See if the given date is available for the location.
     *
     * @param \DateTime   $date
     * 
     * @return bool
     **/
    public function isDateAvailable(\DateTime $date)
    {
        if (in_array($date->format('w'), $this->disabled_weekdays)) {
            return false;
        }

        $disabled_dates = [];

        if (is_array($this->disabled_dates) && count($this->disabled_dates) > 0 && !is_array($this->disabled_dates[0])) {
            $disabled_dates = $this->disabled_dates;
        } 

        if (is_array($this->disabled_dates) && count($this->disabled_dates) > 0 && is_array($this->disabled_dates[0])) {
            $disabled_dates = array_map(function ($a) {
                if ($a['full_day']) {
                    return date('d/m/Y', strtotime($a['date']));
                }
            }, $this->disabled_dates);
        }

        if (in_array($date->format('d/m/Y'), $disabled_dates)) {
            return false;
        }

        return true;
    }
}
