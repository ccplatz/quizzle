<?php

namespace Tests\Feature;

use App\Models\Quiz;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class QuizControllerTest extends TestCase
{
    public function testThatAUserHasToBeLoggedIn(): void
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
}