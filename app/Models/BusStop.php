<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusStop extends Model
{
    use HasFactory;

    protected $fillable = [
        'lat',
        'long',
        'address',
    ];

    public function busSchedules()
    {
        return $this->hasMany(BusSchedule::class);
    }
}
