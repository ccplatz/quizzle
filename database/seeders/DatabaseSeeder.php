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
            ->has(Quiz::factory(2)->state(['number_of_questions' => 10]))
            ->has(Question::factory(3)
                ->has(Answer::factory(3)))
            ->create();

        User::factory()
            ->has(Quiz::factory(2)->state(['number_of_questions' => 10]))
            ->has(Question::factory(11)
                ->has(Answer::factory(4)))
            ->create([
                'name' => 'testuser',
                'email' => 'test@test.de',
            ]);

        foreach (Quiz::all() as $quiz) {
            // link questions to quizzes
            $questionsIdsInCurrentQuiz = $quiz->questions->map->only(['id'])->flatten()->toArray();
            $questionsToAttach = Question::whereNotIn('id', $questionsIdsInCurrentQuiz)->get();
            $questionsIdsToAttach = $questionsToAttach->random($quiz->number_of_questions)->map->only(['id'])->flatten()->toArray();
            $quiz->questions()->attach($questionsIdsToAttach);
            $quiz->refresh();

            // set choices
            foreach ($quiz->questions as $question) {
                $question->quizPosition->answers()->attach($question->answers->random(rand(1, $question->answers->count()))->map->only(['id'])->flatten()->toArray());
            }
            ;
        }
    }
}