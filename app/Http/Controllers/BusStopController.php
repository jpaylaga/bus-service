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
        return $this->busStopRepository->all();
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id)
    {
        return $this->busStopRepository->get($id);
    }
}
