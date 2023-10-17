<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Answer;
use App\Models\Question;
use App\Models\Quiz;
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
            ->count(1)
            ->has(Quiz::factory(2))
            ->has(Question::factory(3)
                ->has(Answer::factory(3)))
            ->create();

        User::factory()
            ->has(Quiz::factory(2))
            ->has(Question::factory(11)
                ->has(Answer::factory(4)))
            ->create([
                'name' => 'testuser',
                'email' => 'test@test.de',
            ]);
    }
}