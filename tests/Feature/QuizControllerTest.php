<?php

namespace Tests\Feature;

use App\Exceptions\NoQuestionAvailableException;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class QuizControllerTest extends TestCase
{
    public function testThatUserHasToBeLoggedIn(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/login');
    }

    public function testThatUserCanCreateQuiz(): void
    {
        $user = User::factory()->create();
        $quiz = Quiz::factory()->make();
        $input = $quiz->toArray();

        $response = $this->actingAs($user)->post(route('quizzes.store'), $input);

        $quiz['user_id'] = $user->id;
        $this->assertDatabaseHas('quizzes', $quiz->toArray());
    }

    public function testThatUserCanSeeHisQuizzes(): void
    {
        User::factory()->has(Quiz::factory(1))->create();
        $user = User::first();
        $response = $this->actingAs($user)->get('/quizzes');

        $response->assertSee('#1');
    }

    public function testThatUserGetsNextQuestion(): void
    {
        $user = User::factory()->create();
        $quiz = Quiz::factory()->make(['number_of_questions' => 10]);
        $question = Question::factory()->create(['user_id' => $user->id]);
        $input = $quiz->toArray();

        $response = $this->actingAs($user)->post(route('quizzes.store'), $input);
        $response->assertRedirect(route('quizzes.next', Quiz::first()));
        $this->followRedirects($response)->assertSee('Question 1 of 10')->assertSee($question->text);
    }

    public function testThatQuizEndsAfterTenQuestions(): void
    {
        $user = User::factory()
            ->has(Quiz::factory(1)->state(['number_of_questions' => 10]))
            ->has(Question::factory(10))
            ->create();
        $quiz = $user->quizzes->first();
        foreach ($user->questions as $question) {
            $quiz->questions()->attach($question->id);
        }

        $response = $this->actingAs($user)->get(route('quizzes.next', $quiz));
        $response->assertRedirect(route('quizzes.result', $quiz));
        $this->followRedirects($response)->assertSee('Your results');
    }

    public function testThatQuizIsStoppedWhenNoNextQuestionAvailable(): void
    {
        $user = User::factory()
            ->has(Quiz::factory(1)->state(['number_of_questions' => 10]))
            ->has(Question::factory(1))
            ->create();
        $quiz = $user->quizzes->first();
        $quiz->questions()->attach($user->questions->first()->id);

        $response = $this->actingAs($user)->get(route('quizzes.next', $quiz));
        $response->assertRedirect(route('home'));
        $this->followRedirects($response)->assertSee('No further question found.');
    }
}