<?php

namespace App\Drivers\BusRoute\Contract;

use App\Models\Bus;
use Carbon\Carbon;
use App\Objects\BusRoute;

interface BusRouteDriver
{
    /**
     * 
     * @param Bus $bus
     * @param Carbon $datetime
     * @return BusRoute
     */
    public function getRoutes(Bus $bus, Carbon $datetime = null): BusRoute;
}
