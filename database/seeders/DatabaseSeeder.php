<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()
            ->count(10)
            ->hasQuizzes(3)
            ->create();

        User::factory()
            ->hasQuizzes(3)
            ->create([
                'name' => '***REMOVED***',
                'email' => '***REMOVED***',
            ]);
    }
}