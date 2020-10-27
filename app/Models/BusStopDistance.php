<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusStopDistance extends Model
{
    use HasFactory;

    protected $fillable = [
        'bus_stop_from_id',
        'bus_stop_to_id',
        'distance_in_km',
    ];
}
