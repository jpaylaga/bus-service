<?php

namespace App\Drivers\BusRoute;

use App\Drivers\BusRoute\Contract\BusRouteDriver as BusRouteDriverContract;
use App\Models\Bus;
use App\Objects\BusRoute;
use Carbon\Carbon;
use App\Repositories\BusStopDistanceRepositoryInterface;
use App\Models\BusStopDistance;

class DefaultDriver implements BusRouteDriverContract
{
    /**
     * @var BusStopDistance
     */
    protected $busStopDistanceRepository;
    
    public function __construct()
    {
        $this->busStopDistanceRepository = resolve(BusStopDistanceRepositoryInterface::class);
    }
    
    /**
     * 
     * {@inheritDoc}
     * @see \App\Drivers\BusRoute\Contract\BusRouteDriver::getRoute()
     */
    public function getRoutes(Bus $bus, Carbon $datetime = null): BusRoute
    {
        return new BusRoute();
    }
}
