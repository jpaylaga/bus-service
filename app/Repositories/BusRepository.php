<?php

namespace App\Repositories;

use App\Models\Bus;
use Illuminate\Database\Eloquent\Collection;
use App\Managers\BusRouteManager;
use App\Objects\BusRoute;
use Carbon\Carbon;

class BusRepository implements BusRepositoryInterface
{
    /**
     * @var BusRouteManager
     */
    protected $busRouteManager;
    
    /**
     * 
     * @param BusRouteManager $busRouteManager
     */
    public function __construct(BusRouteManager $busRouteManager)
    {
        $this->busRouteManager = $busRouteManager;
    }
    
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
    
    /**
     * 
     * {@inheritDoc}
     * @see \App\Repositories\BusRepositoryInterface::getRoutes()
     */
    public function getRoutes(Bus $bus, Carbon $datetime = null): BusRoute
    {
        return $this->busRouteManager->driver()->getRoutes($bus, $datetime);
    }
}
