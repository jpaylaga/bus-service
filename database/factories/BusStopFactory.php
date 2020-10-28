<?php

namespace Database\Factories;

use App\Models\BusStop;
use Illuminate\Database\Eloquent\Factories\Factory;

class BusStopFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BusStop::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'lat' => $this->faker->latitude,
            'long' => $this->faker->longitude,
            'address' => $this->faker->address,
        ];
    }
}
