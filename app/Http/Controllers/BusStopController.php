<?php

namespace App\Http\Controllers;

use App\Http\Resources\BusScheduleCollection;
use App\Repositories\BusStopRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BusStopController extends Controller
{
    /**
     * @var BusStopRepositoryInterface
     */
    protected $busStopRepository;

    /**
     * BusStopController constructor.
     * @param BusStopRepositoryInterface $busStopRepository
     */
    public function __construct(BusStopRepositoryInterface $busStopRepository)
    {
        $this->busStopRepository = $busStopRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        return $this->busStopRepository->nearMe(
                $request->query('lat'),
                $request->query('long'),
                $request->query('radius', 1000)
            );
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return BusScheduleCollection
     */
    public function show(int $id)
    {
        return new BusScheduleCollection(
            $this->busStopRepository->nextArrival(
                $this->busStopRepository->get($id)
            ), $this->busStopRepository);
    }
}
