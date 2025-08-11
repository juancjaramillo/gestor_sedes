<?php

namespace Database\Factories;

use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;

class LocationFactory extends Factory
{
    protected $model = Location::class;

    public function definition(): array
    {
        return [
            'code'  => strtoupper($this->faker->unique()->lexify('???')),
            'name'  => $this->faker->city(),
            'image' => null,
        ];
    }
}
