<?php

namespace App\Repositories;

use App\Models\Bus;
use App\Models\BusSchedule;
use App\Models\BusStop;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;

interface BusStopRepositoryInterface
{
    /**
     * Get's a record by it's ID
     *
     * @param int
     */
    public function get($id);

    /**
     * Get's all records.
     *
     * @return mixed
     */
    public function all();

    /**
     * Deletes a record.
     *
     * @param int
     */
    public function delete($id);

    /**
     * Updates a record.
     *
     * @param int
     * @param array
     */
    public function update($id, array $data);
    
    /**
     * Create a BusStop.
     * 
     * @param array $data
     */
    public function create(array $data);

    /**
     * Get buses near the user.
     * 
     * @param string $lat
     * @param string $long
     */
    public function nearMe($lat, $long);

    /**
     * Get the next arrival per bus stop.
     *
     * @param BusStop $busStop
     * @param Carbon|null $currentDateTime
     * @return Collection
     */
    public function nextArrival(BusStop $busStop, Carbon $currentDateTime = null): Collection;

    /**
     * Calculate ETA.
     *
     * @param BusSchedule $busSchedule
     * @param Carbon|null $currentDateTime
     * @return int
     */
    public function estimatedTimeOfArrivalInMinutes(BusSchedule $busSchedule, Carbon $currentDateTime = null): int;
}
