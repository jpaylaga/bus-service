<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'model',
        'company',
    ];

    public function busSchedules()
    {
        return $this->hasMany(BusSchedule::class);
    }
}
