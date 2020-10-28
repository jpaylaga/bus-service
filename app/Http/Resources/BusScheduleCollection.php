<?php

namespace App\Http\Resources;

use App\Repositories\BusStopRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BusScheduleCollection extends ResourceCollection
{
    /**
     * @var BusStopRepositoryInterface
     */
    protected $busStopRepository;

    /**
     * @var Carbon
     */
    protected $currentDateTime;

    /**
     * BusScheduleCollection constructor.
     * @param $resource
     * @param BusStopRepositoryInterface|null $busStopRepository
     * @param Carbon|null $currentDateTime
     */
    public function __construct(
        $resource,
        BusStopRepositoryInterface $busStopRepository = null,
        Carbon $currentDateTime = null
    ) {
        parent::__construct($resource);

        $this->busStopRepository = $busStopRepository === null ?
            resolve(BusStopRepositoryInterface::class) : $busStopRepository;

        $this->currentDateTime = $currentDateTime;
    }

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $finalBusSchedule = [];
        foreach ($this->collection as $busSchedule) {
            $tmp = $busSchedule->toArray();
            $tmp['eta_in_mins'] =
                $this->busStopRepository->estimatedTimeOfArrivalInMinutes($busSchedule, $this->currentDateTime);
            $finalBusSchedule[] = $tmp;
        }

        return $finalBusSchedule;
    }
}
