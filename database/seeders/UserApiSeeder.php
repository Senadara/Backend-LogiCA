<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User; // Pastikan menggunakan model yang sesuai
use Illuminate\Support\Facades\Hash;

class UserApiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Admin Api',
            'email' => 'adminapi@gmail.com',
            'password' => Hash::make('password'), // Gunakan hashing
            'role' => 'api', // Role khusus
        ]);
    }
}

