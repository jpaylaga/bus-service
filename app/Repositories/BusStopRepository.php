<?php

namespace App\Repositories;

use App\Models\BusSchedule;
use App\Models\BusStop;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class BusStopRepository implements BusStopRepositoryInterface
{
    /**
     * @var BusScheduleRepositoryInterface
     */
    protected $busScheduleRepository;
    
    /**
     * @param BusScheduleRepositoryInterface $busScheduleRepository
     */
    public function __construct(BusScheduleRepositoryInterface $busScheduleRepository)
    {
        $this->busScheduleRepository = $busScheduleRepository;
    }
    
    /**
     * Get's a record by it's ID
     *
     * @param int
     * @return Collection
     */
    public function get($id)
    {
        return BusStop::find($id);
    }

    /**
     * Get's all records.
     *
     * @return mixed
     */
    public function all()
    {
        return BusStop::all();
    }

    /**
     * Deletes a record.
     *
     * @param int
     */
    public function delete($id)
    {
        BusStop::destroy($id);
    }

    /**
     * Updates a post.
     *
     * @param int
     * @param array
     */
    public function update($id, array $data)
    {
        BusStop::find($id)->update($data);
    }
    
    /**
     * Create a BusStop.
     * 
     * @param array $data
     * @return Collection
     */
    public function create(array $data)
    {
        return BusStop::firstOrCreate($data);        
    }

    /**
     * @return mixed|void
     */
    public function nearMe($lat, $long, $radius = 1000)
    {
        $nearByBusStops = collect();
        $nearByBusStopsRequest = $this->requestBusStops($lat, $long, $radius);
        
        $getExisting = BusStop::whereIn('address', $nearByBusStopsRequest->pluck('address'))->get();
        foreach ($nearByBusStopsRequest as $requestedBusStop) {
            $existed = $getExisting->filter(function($v, $K) use ($requestedBusStop) {
                return $requestedBusStop['address'] === $v->address;
            });
            
            if ($existed->count() <= 0) {
                $nearByBusStops->push($this->create($requestedBusStop));
            } else {
                $nearByBusStops->push($existed->first());
            }
        }
        
        // For now I am going to return all bus stops
        return $nearByBusStops;
    }

    /**
     * @param BusStop $busStop
     * @param Carbon|null $currentDateTime
     * @return mixed
     */
    public function nextArrival(BusStop $busStop, Carbon $currentDateTime = null): Collection
    {
        $currentDateTime = $currentDateTime === null ? Carbon::now() : $currentDateTime;
        $currentDayOfWeekCounter = $currentDateTime->dayOfWeek;
        $retryCount = 0;

        do {
            $currentDayOfWeek = BusSchedule::DAYS_OF_WEEK[$currentDayOfWeekCounter];
            $latestSchedule = $this->busScheduleRepository->getLatestSchedule($busStop, $currentDayOfWeek, $currentDateTime);

            if ($currentDayOfWeekCounter < 6) {
                $currentDayOfWeekCounter++;
            } else {
                $currentDayOfWeekCounter = 0;
            }
            $retryCount++;
        } while ($latestSchedule === null && $retryCount < 3);

        if ($latestSchedule === null) {
            return collect();
        }

        return $busStop->busSchedules()->with(['bus', 'busStop'])->where([
            ['time_of_day', '=', $latestSchedule->time_of_day],
            ['day_of_week', '=', $currentDayOfWeek],
            ['is_active', '=', true],
        ])->get();
    }

    /**
     * @param BusSchedule $busSchedule
     * @param Carbon|null $currentDateTime
     * @return int
     */
    public function estimatedTimeOfArrivalInMinutes(BusSchedule $busSchedule, Carbon $currentDateTime = null): int
    {
        $currentDateTime = $currentDateTime === null ? Carbon::now() : $currentDateTime;
        $scheduleToday = Carbon::parse($currentDateTime->format('Y-m-d') . ' ' . $busSchedule->time_of_day);

        return $scheduleToday->diffInMinutes($currentDateTime);
    }
    
    /**
     * Request nearby bus stations.
     * 
     * @param string $lat
     * @param string $long
     * @param int $radius
     * @return NULL[][]
     */
    protected function requestBusStops($lat, $long, $radius)
    {
        $response = \GoogleMaps::load('nearbysearch')
            ->setParam([
                'location' => sprintf("%s, %s", $lat, $long),
                'radius' => $radius,
                'type' => 'bus_station'
            ])
            ->get();
        
        $busStopsReponse = json_decode($response);
        
        $returnValue = collect();
        
        foreach ($busStopsReponse->results as $busStop) {
            $returnValue->push([
                'lat' => $busStop->geometry->location->lat,
                'long' => $busStop->geometry->location->lng,
                'address' => $busStop->name,
            ]);
        }
        
        return $returnValue;
    }
}
