<?php

namespace Database\Factories;

use App\Models\Mechanic;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class MechanicFactory extends Factory
{
    protected $model = Mechanic::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name(),
        ];
    }
}
