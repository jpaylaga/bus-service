<?php

namespace Database\Factories;

use App\Models\BusStopDistance;
use Illuminate\Database\Eloquent\Factories\Factory;

class BusStopDistanceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BusStopDistance::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'bus_stop_from_id' => $this->faker->randomDigit,
            'bus_stop_to_id' => $this->faker->randomDigit,
            'distance_in_km' => $this->faker->randomDigit,
        ];
    }
}
