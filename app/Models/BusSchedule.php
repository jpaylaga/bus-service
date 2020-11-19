<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'bus_id',
        'bus_stop_id',
        'time_of_day',
        'day_of_week',
        'is_active',
    ];

    public const DAYS_OF_WEEK = [
        0 => 'sunday',
        1 => 'monday',
        2 => 'tuesday',
        3 => 'wednesday',
        4 => 'thursday',
        5 => 'friday',
        6 => 'saturday',
    ];

    public function busStop()
    {
        return $this->belongsTo(BusStop::class);
    }

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }
}
