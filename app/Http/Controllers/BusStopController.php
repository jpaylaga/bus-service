<?php

namespace App\Http\Controllers;

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
    public function index()
    {
        return $this->busStopRepository->nearMe(auth()->user());
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function show(int $id)
    {
        return $this->busStopRepository->nextArrival(
            $this->busStopRepository->get($id)
        );
    }
}
