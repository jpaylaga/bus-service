<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\BusSchedule;
use App\Models\BusStop;

interface BusScheduleRepositoryInterface
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
     * Get the latest schedule.
     * 
     * @param BusStop $busStop
     * @param string $currentDayOfWeek
     * @param Carbon $currentDateTime
     * @return BusSchedule
     */
    public function getLatestSchedule(BusStop $busStop, $currentDayOfWeek, Carbon $currentDateTime = null): ?BusSchedule;
}
