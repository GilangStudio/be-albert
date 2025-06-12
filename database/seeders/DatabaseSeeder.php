<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        \App\Models\User::factory()->create([
            'name' => 'Admin',
            'country_code' => '62',
            'phone_number' => '1234567890',
            'birth_date' => '1990-01-01',
            'email' => 'admin@example.com',
            'password' => bcrypt('admin'),
            'is_admin' => true
        ]);
    }
}
