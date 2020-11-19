<?php

namespace App\Objects;

use App\Models\Bus;
use App\Models\BusStop;
use Carbon\Carbon;

class BusRoute
{
    public Bus $bus;
    
    public BusStop $busStop;

    public Carbon $schedule;
    
    public BusRoute $previousRoute;
    
    public BusRoute $nextRoute;
}
