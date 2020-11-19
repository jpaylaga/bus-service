<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Managers\BusRouteManager;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(BusRouteManager::class, function ($app) {
            return new BusRouteManager($app);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
