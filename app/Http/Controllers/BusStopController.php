<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BusStopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        return response()->json([
            'message' => 'In Controller',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function show($id)
    {
        return response()->json([
            'message' => 'In Controller with ID',
        ]);
    }
}
