<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class CacheRecordRepository implements CacheRecordRepositoryInterface
{
    protected $ip;

    private $cacheKeys = [
        'GET_LATEST_SCHEDULE' => 'get_latest_schedule',
        'BUS_STOP_WHEREIN_ADDRESS' => 'bus_stop_wherein_address',
        'BUS_STOP_BUS_SCHEDULE_WITH_BUS_AND_BUS_STOP' => 'bus_stop_bus_schedules_with_bus_and_bus_stop',
    ];

    public function __construct($ip = null)
    {
        if ($ip === null) {
            $ip = request()->ip();
        }
        $this->ip = $ip;
    }

    public function getKeyPrefix(): string
    {
        return 'cache_for_' . $this->ip;
    }

    public function getCacheKey($key): string
    {
        return sprintf("%s_%s",
            $this->getKeyPrefix(),
            $this->cacheKeys[$key]
        );
    }

    public function getExistingKeys(): array
    {
        $existingCacheKeys = [];
        foreach ($this->cacheKeys as $key => $cacheKey) {
            if (Cache::has($this->getCacheKey($key))) {
                $existingCacheKeys[] = $this->getCacheKey($key);
            }
        }

        return $existingCacheKeys;
    }

    public function clearAll($onSpecificIp = true)
    {
        if ($onSpecificIp) {
            foreach ($this->getExistingKeys() as $existingKey) {
                Cache::forget($existingKey);
            }
        } else {
            Artisan::call('cache:clear');
        }
    }
}
