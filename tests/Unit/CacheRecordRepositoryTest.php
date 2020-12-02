<?php

namespace Tests\Unit;

use App\Repositories\CacheRecordRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class CacheRecordRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private $cacheRecordRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cacheRecordRepository = new CacheRecordRepository('127.0.0.1');
    }

    /** @test */
    public function it_can_cache_get_key_prefix()
    {
        $prefix = $this->cacheRecordRepository->getKeyPrefix();
        $this->assertEquals('cache_for_127.0.0.1', $prefix);
    }

    /** @test */
    public function it_can_get_correct_cache_keys()
    {
        $key1 = $this->cacheRecordRepository->getCacheKey('GET_LATEST_SCHEDULE');
        $this->assertEquals('cache_for_127.0.0.1_get_latest_schedule', $key1);

        $key2 = $this->cacheRecordRepository->getCacheKey('BUS_STOP_WHEREIN_ADDRESS');
        $this->assertEquals('cache_for_127.0.0.1_bus_stop_wherein_address', $key2);

        $key3 = $this->cacheRecordRepository->getCacheKey('BUS_STOP_BUS_SCHEDULE_WITH_BUS_AND_BUS_STOP');
        $this->assertEquals('cache_for_127.0.0.1_bus_stop_bus_schedules_with_bus_and_bus_stop', $key3);
    }

    /** @test */
    public function it_can_list_all_cache_keys()
    {
        Artisan::call('cache:clear');

        Cache::put($this->cacheRecordRepository->getCacheKey('GET_LATEST_SCHEDULE'), 'test_data_1');

        $keys = $this->cacheRecordRepository->getExistingKeys();
        $this->assertCount(1, $keys);
        $this->assertEquals('cache_for_127.0.0.1_get_latest_schedule', $keys[0]);

        Cache::put($this->cacheRecordRepository->getCacheKey('BUS_STOP_WHEREIN_ADDRESS'), 'test_data_2');

        $keys = $this->cacheRecordRepository->getExistingKeys();
        $this->assertCount(2, $keys);

        $this->assertEquals('cache_for_127.0.0.1_get_latest_schedule', $keys[0]);
        $this->assertEquals('cache_for_127.0.0.1_bus_stop_wherein_address', $keys[1]);

        Cache::put($this->cacheRecordRepository->getCacheKey('BUS_STOP_BUS_SCHEDULE_WITH_BUS_AND_BUS_STOP'), 'test_data_3');

        $keys = $this->cacheRecordRepository->getExistingKeys();
        $this->assertCount(3, $keys);

        $this->assertEquals('cache_for_127.0.0.1_get_latest_schedule', $keys[0]);
        $this->assertEquals('cache_for_127.0.0.1_bus_stop_wherein_address', $keys[1]);
        $this->assertEquals('cache_for_127.0.0.1_bus_stop_bus_schedules_with_bus_and_bus_stop', $keys[2]);

        $this->cacheRecordRepository->clearAll();

        $keys = $this->cacheRecordRepository->getExistingKeys();
        $this->assertCount(0, $keys);
    }
}
