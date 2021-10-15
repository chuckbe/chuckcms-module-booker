<?php

namespace Chuckbe\ChuckcmsModuleBooker\Providers;

use Chuckbe\ChuckcmsModuleBooker\Chuck\BookerFormRepository;
use Chuckbe\Chuckcms\Models\Module;
use Exception;
use Illuminate\Support\ServiceProvider;

class ChuckcmsModuleBookerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void 
     */
    public function register()
    {
        $this->app->singleton('ChuckModuleBooker',function(){
            $module = Module::where('slug', 'chuckcms-module-booker')->first();
            
            if ($module == null) {
                throw new Exception('Whoops! ChuckCMS Booker Module Not Installed...');
            }

            return new \Chuckbe\ChuckcmsModuleBooker\Chuck\Accessors\ChuckModuleBooker($module, \App::make(BookerFormRepository::class));
        });
    }
}