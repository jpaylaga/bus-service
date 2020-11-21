<?php

namespace Database\Seeders;

use App\Models\Bus;
use App\Models\BusSchedule;
use App\Models\BusStop;
use App\Models\BusStopDistance;
use App\Models\User;
use Illuminate\Database\Seeder;
use App\Repositories\BusStopDistanceRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

class SampleDataSeeder extends Seeder
{
    private $busStopBatches = 5;
    
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
        
        // Aggregiate starting buses by their starting bus stops
        $aggregiate = [];
        
        $busIndex = 0;
        foreach ($busStops as $busStop) {
            $tmp = [
                'bus_stop' => $busStop,
                'buses' => [],
            ];
            for ($i = 0; $i < $this->busStopBatches; $i++) {
                if (isset($buses[$busIndex])) {
                    $tmp['buses'][] = $buses[$busIndex++];
                } else {
                    break;
                }
            }
            $aggregiate[] = $tmp;
        }
        
        // Generate routes
        $repo = resolve(BusStopDistanceRepositoryInterface::class);
        foreach ($aggregiate as $a) {
            foreach ($a['buses'] as $bus) {
                $this->createRoutesPerBus($bus, $a['bus_stop'], $repo);
            }
        }
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
                'location' => '1.364313, 103.991305', // Changi airport
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
    
    private function createRoutesPerBus(Bus $bus, BusStop $startingBusStop, BusStopDistanceRepositoryInterface $busStopDistanceRepository)
    {
        $sortedDistances = $busStopDistanceRepository->getSorted(1000);
        
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
            $visitedIds = [];
            $previousDistance = null;
            $currentDistance = null;
            $startOfDay = Carbon::now()->startOfDay();
            
            // Get starting key
            foreach ($sortedDistances as $distance) {
                if ($distance->bus_stop_to_id === $startingBusStop->id) {
                    $visitedIds = $this->generateSchedule($bus, $distance, $sortedDistances, $startOfDay, $day, $visitedIds, $currentDistance);
                    break;
                }
            }
            
            for ($i = 0; $i < $sortedDistances->count(); $i++) {
                foreach ($sortedDistances as $distance) {
                    if ($distance->bus_stop_from_id === $currentDistance->bus_stop_to_id && !in_array($distance->id, $visitedIds)) {
                        $visitedIds = $this->generateSchedule($bus, $distance, $sortedDistances, $startOfDay, $day, $visitedIds, $currentDistance);
                        break;
                    }
                }
            }
        }
    }
    
    private function generateSchedule(
        Bus $bus,
        BusStopDistance $distance,
        LengthAwarePaginator $sortedDistances,
        Carbon $startOfDay,
        $day,
        array $visitedIds,
        ?BusStopDistance &$currentDistance
    ) {
        $currentDistance = $distance;
            
        $startOfDay = $startOfDay->addMinutes($distance->eta_in_mins + 10);
        $cmpDateTime = Carbon::now()->startOfDay();
        
        if ($cmpDateTime->diffInHours($startOfDay) < 24) {
            BusSchedule::factory()->create([
                'bus_id' => $bus->id,
                'bus_stop_id' => $currentDistance->bus_stop_to_id,
                'day_of_week' => $day,
                'time_of_day' => $startOfDay->format('H:i:s'),
            ]);
            
            $visitedIds[] = $distance->id;
            
            // Consider the inverse distance as visited
            $inverse = $sortedDistances->filter(function ($v, $k) use ($distance) {
                return $v->bus_stop_from_id === $distance->bus_stop_to_id && $v->bus_stop_to_id === $distance->bus_stop_from_id;
            });
            if ($inverse->count() > 0) {
                $visitedIds[] = $inverse->first()->id;
            }
        }
        
        return $visitedIds;
    }
}
