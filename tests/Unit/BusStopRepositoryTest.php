<?php

namespace Tests\Unit;

use App\Models\Bus;
use App\Models\BusSchedule;
use App\Models\BusStop;
use App\Models\User;
use App\Repositories\BusStopRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BusStopRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private $busStopRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->busStopRepository = resolve(BusStopRepositoryInterface::class);
    }

    /** @test */
    public function it_can_find_bus_stops_near_me()
    {
        BusStop::factory()->count(3)->create();

        $busStopsNearMe = $this->busStopRepository->nearMe('1.364313', '103.991305', 1000000);

        $this->assertCount(20, $busStopsNearMe);
        
        $currentBusStops = $this->busStopRepository->all();
        
        $this->assertCount(23, $currentBusStops);
        
        $busStopsNearMeShorterRadius = $this->busStopRepository->nearMe('1.364313', '103.991305', 1000);
        
        $this->assertCount(1, $busStopsNearMeShorterRadius);
    }

    /** @test */
    public function it_can_get_next_arrival_per_bus_stop()
    {
        $bus1 = Bus::factory()->create();
        $busStop1 = BusStop::factory()->create();

        $bs1 = BusSchedule::factory()->state([
            'bus_id' => $bus1->id,
            'bus_stop_id' => $busStop1->id,
            'time_of_day' => '01:00:00',
            'day_of_week' => 'monday',
        ])->create();

        $currentDateTime = Carbon::parse('2020-10-26 00:50:00');

        $bsOutput1 = $this->busStopRepository->nextArrival($busStop1, $currentDateTime);
        $this->assertCount(1, $bsOutput1);
        $bso1 = $bsOutput1->first();
        $this->assertEquals($bs1->id, $bso1->id);
        $this->assertEquals($bs1->bus_id, $bso1->bus_id);
        $this->assertEquals($bs1->bus_stop_id, $bso1->bus_stop_id);
        $this->assertEquals($bs1->time_of_day, $bso1->time_of_day);
        $this->assertEquals($bs1->day_of_week, $bso1->day_of_week);

        $bus2 = Bus::factory()->create();

        $bs2 = BusSchedule::factory()->state([
            'bus_id' => $bus2->id,
            'bus_stop_id' => $busStop1->id,
            'time_of_day' => '01:00:00',
            'day_of_week' => 'monday',
        ])->create();

        $bsOutput2 = $this->busStopRepository->nextArrival($busStop1, $currentDateTime);
        $this->assertCount(2, $bsOutput2);
        $bso2_1 = $bsOutput2->first();
        $bso2_2 = $bsOutput2->last();

        $this->assertEquals($bs1->id, $bso2_1->id);
        $this->assertEquals($bs1->bus_id, $bso2_1->bus_id);
        $this->assertEquals($bs1->bus_stop_id, $bso2_1->bus_stop_id);
        $this->assertEquals($bs1->time_of_day, $bso2_1->time_of_day);
        $this->assertEquals($bs1->day_of_week, $bso2_1->day_of_week);

        $this->assertEquals($bs2->id, $bso2_2->id);
        $this->assertEquals($bs2->bus_id, $bso2_2->bus_id);
        $this->assertEquals($bs2->bus_stop_id, $bso2_2->bus_stop_id);
        $this->assertEquals($bs2->time_of_day, $bso2_2->time_of_day);
        $this->assertEquals($bs2->day_of_week, $bso2_2->day_of_week);
    }

    /** @test */
    public function it_can_calculate_eta_in_minutes()
    {
        $currentDateTime = Carbon::parse('2020-10-26 00:50:00');

        $bus1 = Bus::factory()->create();
        $busStop1 = BusStop::factory()->create();

        $bs1 = BusSchedule::factory()->state([
            'bus_id' => $bus1->id,
            'bus_stop_id' => $busStop1->id,
            'time_of_day' => '01:00:00',
        ])->create();

        $this->assertEquals(10, $this->busStopRepository->estimatedTimeOfArrivalInMinutes($bs1, $currentDateTime));
    }
}
