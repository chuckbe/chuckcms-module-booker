<?php 

namespace Chuckbe\ChuckcmsModuleBooker\Facades;

use Illuminate\Support\Facades\Facade;

class ChuckModuleBooker extends Facade {
    /**
     * Return facade accessor
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'ChuckModuleBooker';
    }
}