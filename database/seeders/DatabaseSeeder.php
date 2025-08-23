<?php

namespace Database\Seeders;

use App\Models\PhotoModel;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create 10 users
        User::factory(10)->create();

        // Create 50 photos linked to random users
        PhotoModel::factory(200)->create();
    }
}
