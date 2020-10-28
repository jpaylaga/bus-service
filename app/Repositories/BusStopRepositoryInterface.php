<?php

namespace App\Repositories;

use App\Models\Bus;
use App\Models\BusSchedule;
use App\Models\BusStop;
use App\Models\User;
use Carbon\Carbon;

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
     * Get buses near the user.
     *
     * @param User $user
     * @return mixed
     */
    public function nearMe(User $user);

    /**
     * Get the next arrival per bus stop.
     *
     * @param BusStop $busStop
     * @param Carbon|null $currentDateTime
     * @return BusSchedule
     */
    public function nextArrival(BusStop $busStop, Carbon $currentDateTime = null): BusSchedule;

    /**
     * Calculate ETA.
     *
     * @param BusSchedule $busSchedule
     * @param Carbon|null $currentDateTime
     * @return int
     */
    public function estimatedTimeOfArrivalInMinutes(BusSchedule $busSchedule, Carbon $currentDateTime = null): int;
}
