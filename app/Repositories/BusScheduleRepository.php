<?php


namespace App\Repositories;

use App\Models\BusSchedule;
use Illuminate\Database\Eloquent\Collection;


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
}
