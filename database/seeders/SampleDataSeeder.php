<?php

namespace Database\Seeders;

use App\Models\Bus;
use App\Models\BusSchedule;
use App\Models\BusStop;
use App\Models\BusStopDistance;
use Illuminate\Database\Seeder;
use phpDocumentor\Reflection\Types\Collection;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create 3 buses
        $buses = Bus::factory()->count(3)->create();
        $busStops = BusStop::factory()->count(9)->create();

        // Distances
        foreach ($busStops as $busStopFrom) {
            foreach ($busStops as $busStopTo) {
                if ($busStopFrom->id !== $busStopTo->id) {
                    BusStopDistance::factory()->state([
                        'bus_stop_from_id' => $busStopFrom->id,
                        'bus_stop_to_id' => $busStopTo->id,
                    ])->create();
                }
            }
        }

        // Schedules
        foreach ($buses as $bus) {
            foreach ($busStops as $busStop) {
                $daysOfWeek = [
                    'sunday',
                    'monday',
                    'tuesday',
                    'wednesday',
                    'thursday',
                    'friday',
                    'saturday',
                ];

                foreach ($daysOfWeek as $day) {
                    BusSchedule::factory()->count(3)->state([
                        'bus_id' => $bus->id,
                        'bus_stop_id' => $busStop->id,
                        'day_of_week' => $day,
                    ])->create();
                }
            }
        }
    }
}
