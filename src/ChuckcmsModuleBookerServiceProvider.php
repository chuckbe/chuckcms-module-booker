<?php

namespace Chuckbe\ChuckcmsModuleBooker;

use Chuckbe\ChuckcmsModuleBooker\Commands\InstallModuleBooker;
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
        $this->loadRoutesFrom(__DIR__.'/routes/routes.php');

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->publishes([
            __DIR__.'/../assets' => public_path('chuckbe/chuckcms-module-booker'),
        ], 'chuckcms-module-booker-public');

        $this->publishes([
            __DIR__ . '/../config/chuckcms-module-booker.php' => config_path('chuckcms-module-booker.php'),
        ], 'chuckcms-module-booker-config');

        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallModuleBooker::class,
            ]);
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->loadViewsFrom(__DIR__.'/views', 'chuckcms-module-booker');

        $this->app->register(
            'Chuckbe\ChuckcmsModuleBooker\Providers\ChuckcmsModuleBookerServiceProvider'
        );

        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('ChuckModuleBooker', 'Chuckbe\ChuckcmsModuleBooker\Facades\ChuckModuleBooker');
        $this->mergeConfigFrom(
            __DIR__ . '/../config/chuckcms-module-booker.php', 'chuckcms-module-booker'
        );
    }
}