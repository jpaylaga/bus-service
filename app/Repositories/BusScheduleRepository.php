<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\BusSchedule;
use App\Models\BusStop;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Artisan;

class BusScheduleRepository implements BusScheduleRepositoryInterface
{
    /**
     * Get's a record by it's ID
     *
     * @param int
     * @return Collection
     */
    public function get($id)
    {
        return BusSchedule::find($id);
    }

    /**
     * Get's all records.
     *
     * @return mixed
     */
    public function all()
    {
        return BusSchedule::all();
    }

    /**
     * Deletes a record.
     *
     * @param int
     */
    public function delete($id)
    {
        BusSchedule::destroy($id);
    }

    /**
     * Updates a post.
     *
     * @param int
     * @param array
     */
    public function update($id, array $data)
    {
        BusSchedule::find($id)->update($data);
    }

    /**
     * @param array $data
     * @return BusSchedule
     * @throws \Symfony\Component\Console\Exception\CommandNotFoundException
     */
    public function create(array $data): BusSchedule
    {
        Artisan::call('cache:clear');
        return BusSchedule::create($data);
    }

    /**
     * {@inheritDoc}
     * @see \App\Repositories\BusScheduleRepositoryInterface::getLatestSchedule()
     */
    public function getLatestSchedule(BusStop $busStop, $currentDayOfWeek, Carbon $currentDateTime = null): ?BusSchedule
    {
        $currentDateTime = $currentDateTime === null ? Carbon::now() : $currentDateTime;
        return $busStop->busSchedules()->where([
            ['time_of_day', '>=', $currentDateTime->format('H:i:s')],
            ['day_of_week', '=', $currentDayOfWeek],
        ])->orderBy('time_of_day', 'asc')->first();
    }
}
