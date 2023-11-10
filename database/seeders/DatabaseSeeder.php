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
        \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'pasword' => '12345'
        ]);

        \App\Models\Video::factory(10)->create();

        \App\Models\Video::factory()->create([
            'title' => 'Test User',
            'description' => 'test@example.com',
            'user_id' => '12345'
        ]);
    }
}
