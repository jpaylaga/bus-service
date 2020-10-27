<?php

namespace App\Repositories;

use App\Models\BusStopDistance;
use Illuminate\Database\Eloquent\Collection;

class BusStopDistanceRepository implements BusStopDistanceRepositoryInterface
{
    /**
     * Get's a record by it's ID
     *
     * @param int
     * @return Collection
     */
    public function get($id)
    {
        return BusStopDistance::find($id);
    }

    /**
     * Get's all records.
     *
     * @return mixed
     */
    public function all()
    {
        return BusStopDistance::all();
    }

    /**
     * Deletes a record.
     *
     * @param int
     */
    public function delete($id)
    {
        BusStopDistance::destroy($id);
    }

    /**
     * Updates a post.
     *
     * @param int
     * @param array
     */
    public function update($id, array $data)
    {
        BusStopDistance::find($id)->update($data);
    }
}
