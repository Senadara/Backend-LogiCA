<?php

namespace Database\Seeders;

use App\Models\Mechanic;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->count(10)->create();
        Vehicle::factory()->count(5)->create();
        Mechanic::factory()->count(5)->create();
        $this->call(UserApiSeeder::class);
    }
}
