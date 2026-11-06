<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hindari duplikasi
        if (!User::where('email', 'user@maullstore.com')->exists()) {
            User::create([
                'name' => 'Maull Hebat',
                'email' => 'user@maullstore.com',
                'password' => Hash::make('password123'),
            ]);
        }
    }
}
