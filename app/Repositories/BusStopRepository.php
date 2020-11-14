<?php

namespace App\Repositories;

use App\Models\BusSchedule;
use App\Models\BusStop;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class BusStopRepository implements BusStopRepositoryInterface
{
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
     * @return mixed|void
     */
    public function nearMe()
    {
        // For now I am going to return all bus stops
        return $this->all();
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

            // This query can be optimized
            $latestSchedule = $busStop->busSchedules()->where([
                ['time_of_day', '>=', $currentDateTime->format('H:i:s')],
                ['day_of_week', '=', $currentDayOfWeek],
            ])->first();

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
}
