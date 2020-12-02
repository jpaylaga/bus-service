<?php

namespace App\Providers;

use App\Repositories\BusRepository;
use App\Repositories\BusRepositoryInterface;
use App\Repositories\BusScheduleRepository;
use App\Repositories\BusScheduleRepositoryInterface;
use App\Repositories\BusStopDistanceRepository;
use App\Repositories\BusStopDistanceRepositoryInterface;
use App\Repositories\BusStopRepository;
use App\Repositories\BusStopRepositoryInterface;
use App\Repositories\CacheRecordRepository;
use App\Repositories\CacheRecordRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryBackendServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind(
           BusRepositoryInterface::class,
            BusRepository::class,
        );

        $this->app->bind(
            BusScheduleRepositoryInterface::class,
            BusScheduleRepository::class,
        );

        $this->app->bind(
            BusStopRepositoryInterface::class,
            BusStopRepository::class,
        );

        $this->app->bind(
            BusStopDistanceRepositoryInterface::class,
            BusStopDistanceRepository::class,
        );

        $this->app->bind(
            CacheRecordRepositoryInterface::class,
            CacheRecordRepository::class
        );
    }
}
