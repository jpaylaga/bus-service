<?php

namespace App\Repositories;

use App\Models\Bus;
use App\Objects\BusRoute;
use App\Drivers\BusRoute\Contract\BusRouteDriver;

interface BusRepositoryInterface extends BusRouteDriver
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
}
