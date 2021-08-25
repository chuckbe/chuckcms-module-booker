<?php

namespace Chuckbe\ChuckcmsModuleBooker\Providers;

use Chuckbe\ChuckcmsModuleBooker\Chuck\BookerFormRepository;
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
            return new \Chuckbe\ChuckcmsModuleBooker\Chuck\Accessors\ChuckModuleBooker(\App::make(BookerFormRepository::class));
        });
    }
}