<?php

namespace Tests\Feature;

use App\Exceptions\NoQuestionAvailableException;
use App\Models\Answer;
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

    public function testThatQuizIsStoppedWhenNoNextQuestionAvailable(): void
    {
        $user = User::factory()
            ->has(Quiz::factory(1)->state(['number_of_questions' => 10]))
            ->has(Question::factory(1)->has(Answer::factory(1)))
            ->create();
        $quiz = $user->quizzes->first();
        $question = $user->questions->first();
        $quiz->questions()->attach($question->id);
        $quiz->refresh();
        // Attach an answer to the quizPosition to make it complete
        $quiz->questions->first()->quizPosition->answers()->attach(Answer::where('question_id', $question->id)->first());

        $response = $this->actingAs($user)->get(route('quizzes.next', $quiz));
        $response->assertRedirect(route('home'));
        $this->followRedirects($response)->assertSee('No further question found.');
    }

    public function testThatQuizShowsOpenQuestionIfOpenAvailable(): void
    {
        $user = User::factory()
            ->has(Quiz::factory(1)->state(['number_of_questions' => 10]))
            ->has(Question::factory(1))
            ->create();
        $quiz = $user->quizzes->first();
        $question = $user->questions->first();
        $quiz->questions()->attach($question->id);

        $response = $this->actingAs($user)->get(route('quizzes.next', $quiz));
        $response->assertSee('Question 1 of 10')->assertSee($question->text);

        $response = $this->actingAs($user)->get(route('quizzes.next', $quiz));
        $response->assertSee('Question 1 of 10')->assertSee($question->text);
    }

    public function testThatQuizGetsNoNewQuestionIfOpenAvailable(): void
    {
        $user = User::factory()
            ->has(Quiz::factory(1)->state(['number_of_questions' => 10]))
            ->has(Question::factory(2))
            ->create();
        $quiz = $user->quizzes->first();
        $question = $user->questions->first();
        $quiz->questions()->attach($question->id);
        $quiz->refresh();

        $response = $this->actingAs($user)->get(route('quizzes.next', $quiz));
        $this->assertTrue($quiz->questions->count() === 1);
    }

    public function testThatQuizIsFinishedIfEveryQuestionHasAnAnswer(): void
    {
        $user = User::factory()
            ->has(Quiz::factory(1)->state(['number_of_questions' => 10]))
            ->has(Question::factory(11)->has(Answer::factory(2)))
            ->create();
        $quiz = $user->quizzes->first();
        $questionIds = Question::all()->take(10)->map->only(['id'])->flatten()->toArray();
        $quiz->questions()->attach($questionIds);
        $quiz->refresh();
        foreach ($quiz->questions as $question) {
            $question->quizPosition->answers()->attach($question->answers->first()->id);
        }

        $response = $this->actingAs($user)->get(route('quizzes.next', $quiz));
        $response->assertRedirect(route('quizzes.result', $quiz));
    }

    public function testThatUserCanStoreChoices(): void
    {
        $user = User::factory()
            ->has(Quiz::factory(1)->state(['number_of_questions' => 10]))
            ->has(Question::factory(1)->has(Answer::factory(2)))
            ->create();
        $quiz = $user->quizzes->first();
        $questionIds = Question::all()->take(10)->map->only(['id'])->flatten()->toArray();
        $quiz->questions()->attach($questionIds);
        $quiz->refresh();
        $data = [
            'quizPosition' => $quiz->questions->first()->quizPosition->id,
            'choices' => [$quiz->questions->first()->answers->first()->id]
        ];

        $response = $this->actingAs($user)->post(route('quizzes.store-choices', $quiz), $data);
        $this->assertDatabaseHas(
            'choice',
            [
                'quiz_position_id' => $data['quizPosition'],
                'answer_id' => $data['choices'][0]
            ]
        );
    }
}