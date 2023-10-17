<?php

namespace Tests\Feature;

use App\Models\Answer;
use App\Models\Question;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class QuestionControllerTest extends TestCase
{
    public function testThatAUserHasToBeLoggedIn(): void
    {
        $response = $this->get('/questions');

        $response->assertRedirect('/login');
    }

    public function testThatLoggedInUserCanReachHome(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
    }

    public function testThatUserCanCreateQuestion(): void
    {
        $user = User::factory()->create();
        $question = Question::factory()->make();
        $answers = Answer::factory(2)->correct()->make();
        $input = $question->toArray();
        $input['answers']['A'] = $answers->first()->toArray();
        $input['answers']['B'] = $answers->last()->toArray();

        $response = $this->actingAs($user)->post(route('questions.store'), $input);

        $this->assertDatabaseHas('questions', $question->toArray());
    }

    public function testThatUserCanSeeHisQuestions(): void
    {
        User::factory()->has(Question::factory(1))->create();
        $user = User::first();
        $response = $this->actingAs($user)->get('/questions');

        $response->assertSee('Question #1');
    }

    public function testThatUserCanReachEditQuestion(): void
    {
        User::factory()->has(Question::factory(1))->create();
        $user = User::first();
        $question = $user->questions->first();
        $response = $this->actingAs($user)->get(route('questions.edit', $question));

        $response->assertSee('Edit question');
    }

    public function testThatUserCanEditHisQuestion(): void
    {
        $this->seed();
        $user = User::first();
        $question = $user->questions->first();
        $answers = Answer::factory(2)->correct()->make();
        $newQuestion = Question::factory()->make();
        $input = $newQuestion->toArray();
        $input['answers']['A'] = $answers->first()->toArray();
        $input['answers']['B'] = $answers->last()->toArray();

        $response = $this->actingAs($user)->patch(route('questions.update', $question), $input);

        $this->assertDatabaseMissing('questions', ['text' => $question->text]);
        $this->assertDatabaseHas('questions', $newQuestion->toArray());
    }

    public function testThatUserCannotEditAnotherUsersQuestion(): void
    {
        User::factory(2)->has(Question::factory(1))->create();
        $user1 = User::first();
        $user2 = User::all()->last();
        $question = $user1->questions->first();
        $newQuestion = Question::factory()->make();

        $response = $this->actingAs($user2)->patch(route('questions.update', $question), $newQuestion->toArray());

        $response->assertForbidden();
    }

    public function testThatUserCannotViewAnotherUsersQuestion(): void
    {
        User::factory(2)->has(Question::factory(1))->create();
        $user1 = User::first();
        $user2 = User::all()->last();
        $question = $user1->questions->first();

        $response = $this->actingAs($user2)->get(route('questions.edit', $question));

        $response->assertForbidden();
    }

    public function testThatUserCannotDeleteAnotherUsersQuestion(): void
    {
        User::factory(2)->has(Question::factory(1))->create();
        $user1 = User::first();
        $user2 = User::all()->last();
        $question = $user1->questions->first();

        $response = $this->actingAs($user2)->delete(route('questions.destroy', $question));

        $response->assertForbidden();
    }

    public function testThatUserCanDeleteHisQuestion(): void
    {
        User::factory(2)->has(Question::factory(1))->create();
        $user = User::first();
        $question = $user->questions->first();

        $response = $this->actingAs($user)->delete(route('questions.destroy', $question));

        $this->assertDatabaseMissing('questions', $question->toArray());
    }
}