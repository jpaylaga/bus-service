<?php

namespace App\Repositories;

interface BusStopDistanceRepositoryInterface
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
     * Get sorted 
     * 
     * @param int $perPage
     */
    public function getSorted(int $perPage = 20);
}
