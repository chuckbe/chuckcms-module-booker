<?php

namespace Chuckbe\ChuckcmsModuleBooker\Chuck\Accessors;

use Chuckbe\ChuckcmsModuleBooker\Chuck\BookerFormRepository;
use Exception;
use Chuckbe\Chuckcms\Models\Module;
use Illuminate\Support\Facades\Schema;

use App\Http\Requests;

class ChuckModuleBooker
{
    private $bookerFormRepository;
    private $module;
    private $moduleSettings;

    public function __construct(Module $module, BookerFormRepository $bookerFormRepository) 
    {
        $this->module = $module;
        $this->bookerFormRepository = $bookerFormRepository;
        $this->moduleSettings = $this->getModuleSettings($module);
    }

    public function renderStyles()
    {
        return $this->bookerFormRepository->styles();
    }

    public function renderForm()
    {
        return $this->bookerFormRepository->render();
    }

    public function renderScripts()
    {
        return $this->bookerFormRepository->scripts();
    }

    /**
     * Return the module object
     *
     **/
    public function get()
    {
        return $this->module;
    }

    /**
     * Return the settings array
     *
     * @var Module $module
     **/
    private function getModuleSettings(Module $module)
    {
        return $module->settings ?? [];
    }

    public function getSetting($var)
    {
        $setting = $this->resolveSetting($var, $this->moduleSettings);
        return $setting !== 'undefined' ? $setting : null;
    }

    public function setSetting($var, $value)
    {
        $this->updateSetting($var, $this->moduleSettings, $value);    
    }

    private function resolveSetting($var, $settings)
    {
        $split = explode('.', $var);
        foreach ($split as $value) {
            if (array_key_exists($value, $settings)) {
                $settings = $settings[$value];
            } else {
                return 'undefined';
            }
        }

        return $settings;
    }

    private function updateSetting($var, $settings_object, $input)
    {
        $split = explode('.', $var);
        $settings = $settings_object;
        $level = count($split);

        if($level == 1) {
            $settings[$split[0]] = $input;
        } elseif($level == 2) {
            $settings[$split[0]][$split[1]] = $input;
        } elseif($level == 3) {
            $settings[$split[0]][$split[1]][$split[2]] = $input;
        } elseif($level == 4) {
            $settings[$split[0]][$split[1]][$split[2]][$split[3]] = $input;
        } elseif($level == 5) {
            $settings[$split[0]][$split[1]][$split[2]][$split[3]][$split[4]] = $input;
        }

        $json = $this->module->json;
        $json['settings'] = $settings;
        $this->module->json = $json;
        $this->module->update();
    }

    public function formatPrice($var)
    {
        return '€ ' . number_format((float)$var, (int)$this->getSetting('general.decimals'), $this->getSetting('general.decimals_separator'), $this->getSetting('general.thousands_separator'));
    }

    public function priceWithoutTax($price, $tax)
    {
        $newprice = $price / ((100 + $tax) / 100);
        return round($newprice, 2);
    }

    public function taxFromPrice($price, $tax)
    {
        $newprice = ($price / (100 + $tax)) * $tax;
        return round($newprice, 2);
    }
}