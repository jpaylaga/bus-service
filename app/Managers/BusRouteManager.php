<?php

namespace App\Managers;

use Illuminate\Support\Manager;
use App\Drivers\BusRoute\DefaultDriver;

class BusRouteManager extends Manager
{
    public function getDefaultDriver()
    {
        return 'default';
    }
    
    public function createDefaultDriver()
    {
        return new DefaultDriver();
    }
}
