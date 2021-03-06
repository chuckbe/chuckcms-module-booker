<?php

namespace Chuckbe\ChuckcmsModuleBooker;

use Chuckbe\ChuckcmsModuleBooker\Commands\ExpireSubscriptions;
use Chuckbe\ChuckcmsModuleBooker\Commands\InstallModuleBooker;
use Chuckbe\ChuckcmsModuleBooker\Commands\RenewSubscriptions;
use Illuminate\Support\ServiceProvider;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;

class ChuckcmsModuleBookerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/chuckcms-module-booker.php', 'chuckcms-module-booker'
        );

        $this->loadRoutesFrom(__DIR__.'/routes/routes.php');

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->doPublishing();

        if ($this->app->runningInConsole()) {
            $this->commands([
                ExpireSubscriptions::class,
                InstallModuleBooker::class,
                RenewSubscriptions::class,
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
    }

    public function doPublishing()
    {
        if (!function_exists('config_path')) {
            // function not available and 'publish' not relevant in Lumen (credit: Spatie)
            return;
        }

        $this->publishes([
            __DIR__.'/../config/chuckcms-module-booker.php' => config_path('chuckcms-module-booker.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/../assets' => public_path('module-booker'),
        ], 'assets');

        $this->publishes([
            __DIR__.'/../database/migrations/create_services_table.php.stub' => $this->getMigrationFileName('create_services_table.php'),
            __DIR__.'/../database/migrations/create_locations_table.php.stub' => $this->getMigrationFileName('create_locations_table.php'),
            __DIR__.'/../database/migrations/create_customers_table.php.stub' => $this->getMigrationFileName('create_customers_table.php'),
        ], 'migrations');

        sleep(1);

        $this->publishes([
            __DIR__.'/../database/migrations/create_appointments_table.php.stub' => $this->getMigrationFileName('create_appointments_table.php'),
            __DIR__.'/../database/migrations/create_appointments_services_table.php.stub' => $this->getMigrationFileName('create_appointments_services_table.php'),
            __DIR__.'/../database/migrations/create_gift_cards_table.php.stub' => $this->getMigrationFileName('create_gift_cards_table.php'),
            __DIR__.'/../database/migrations/create_locations_services_table.php.stub' => $this->getMigrationFileName('create_locations_services_table.php'),
            __DIR__.'/../database/migrations/create_subscription_plans_table.php.stub' => $this->getMigrationFileName('create_subscription_plans_table.php'),
            __DIR__.'/../database/migrations/create_subscriptions_table.php.stub' => $this->getMigrationFileName('create_subscriptions_table.php'),
        ], 'after_migrations');

        sleep(1);

        $this->publishes([
            __DIR__.'/../database/migrations/create_payments_table.php.stub' => $this->getMigrationFileName('create_payments_table.php'),
        ], 'after_after_migrations');

    }


    /**
     * Returns existing migration file if found, else uses the current timestamp.
     *
     * @return string
     */
    public function getMigrationFileName($migrationFileName): string
    {
        $timestamp = date('Y_m_d_His');

        $filesystem = $this->app->make(Filesystem::class);

        return Collection::make($this->app->databasePath().DIRECTORY_SEPARATOR.'migrations'.DIRECTORY_SEPARATOR)
            ->flatMap(function ($path) use ($filesystem, $migrationFileName) {
                return $filesystem->glob($path.'*_'.$migrationFileName);
            })
            ->push($this->app->databasePath()."/migrations/{$timestamp}_{$migrationFileName}")
            ->first();
    }
}