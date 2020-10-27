<?php

namespace App\Repositories;

use App\Models\Bus;
use Illuminate\Database\Eloquent\Collection;

class BusRepository implements BusRepositoryInterface
{
    /**
     * Get's a record by it's ID
     *
     * @param int
     * @return Collection
     */
    public function get($id)
    {
        return Bus::find($id);
    }

    /**
     * Get's all records.
     *
     * @return mixed
     */
    public function all()
    {
        return Bus::all();
    }

    /**
     * Deletes a record.
     *
     * @param int
     */
    public function delete($id)
    {
        Bus::destroy($id);
    }

    /**
     * Updates a post.
     *
     * @param int
     * @param array
     */
    public function update($id, array $data)
    {
        Bus::find($id)->update($data);
    }
}
