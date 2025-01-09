<?php

namespace Database\Factories;

use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

class VehicleFactory extends Factory
{
    protected $model = Vehicle::class;

    public function definition()
    {
        return [
            'license_plate' => $this->faker->word() . ' ' . $this->faker->randomNumber(3),
            'status' => 'available',
            'last_maintenance_date' => now()->subDays(rand(1, 100)),
        ];
    }
}
