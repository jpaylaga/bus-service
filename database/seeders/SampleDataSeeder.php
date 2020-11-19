<?php

namespace Database\Seeders;

use App\Models\Bus;
use App\Models\BusSchedule;
use App\Models\BusStop;
use App\Models\BusStopDistance;
use App\Models\User;
use Illuminate\Database\Seeder;
use phpDocumentor\Reflection\Types\Collection;
use App\Repositories\BusStopDistanceRepositoryInterface;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Buses
        $buses = collect();
        for ($i = 0; $i < 4; $i++) {
            foreach ($this->busOperators() as $operator) {
                $buses->push(Bus::factory()->create([
                    'company' => $operator
                ]));
            }
        }
        
        // Bus stops
        $busStops = collect();
        foreach ($this->busStops() as $busStop) {
            $busStops->push(BusStop::factory()->create($busStop));
        }

        // Distances between bus tops
        foreach ($busStops as $busStopFrom) {
            foreach ($busStops as $busStopTo) {
                if ($busStopFrom->id !== $busStopTo->id) {
                    $details = $this->getDistanceDetail($busStopFrom, $busStopTo);
                    BusStopDistance::factory()->create([
                        'bus_stop_from_id' => $busStopFrom->id,
                        'bus_stop_to_id' => $busStopTo->id,
                        'distance_in_km' => $details['distance'],
                        'eta_in_mins' => $details['duration'],
                    ]);
                }
            }
        }

        // Schedules
        $busStopDistanceRepository = resolve(BusStopDistanceRepositoryInterface::class);
        foreach ($busStopDistanceRepository->all() as $distance) {
            
        }
        
        /* foreach ($buses as $bus) {
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
        } */
    }
    
    private function busOperators()
    {
        return [
            'SBS Transit',
            'SMRT Buses',
            'Tower Transit Singapore',
            'Go-Ahead Singapore',
        ];
    }
    
    private function busStops()
    {
        $response = \GoogleMaps::load('nearbysearch')
            ->setParam([
                'location' => '1.364313, 103.991305',
                'radius' => 1000000,
                'type' => 'bus_station'
            ])
            ->get();
        
        $busStopsReponse = json_decode($response);
        
        $returnValue = [];
        foreach ($busStopsReponse->results as $busStop) {
            $returnValue[] = [
                'lat' => $busStop->geometry->location->lat,
                'long' => $busStop->geometry->location->lng,
                'address' => $busStop->name,
            ];
        }
        
        return $returnValue;
        
    }
    
    private function getDistanceDetail(BusStop $busStopFrom, BusStop $busStopTo)
    {
        $param = [
            'origins' => sprintf("%s,%s", $busStopFrom->lat, $busStopFrom->long),
            'destinations' => sprintf("%s,%s", $busStopTo->lat, $busStopTo->long),
        ];
        $response = \GoogleMaps::load('distancematrix')
            ->setParam($param)
            ->get();
        
        $distanceObj = json_decode($response);
        
        return [
            'distance' => $distanceObj->rows[0]->elements[0]->distance->value / 1000,
            'duration' => floor($distanceObj->rows[0]->elements[0]->duration->value / 60),
        ];
    }
}
