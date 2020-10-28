<?php

namespace Database\Factories;

use App\Models\BusSchedule;
use Illuminate\Database\Eloquent\Factories\Factory;

class BusScheduleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BusSchedule::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'bus_id' => $this->faker->randomDigit,
            'bus_stop_id' => $this->faker->randomDigit,
            'time_of_day' => $this->faker->time(),
            'day_of_week' => strtolower($this->faker->dayOfWeek),
        ];
    }
}
