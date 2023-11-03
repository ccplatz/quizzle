<?php

namespace App\Services;

use App\Exceptions\NoQuestionAvailableException;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Support\Collection;

class QuizService
{
    /**
     * Get the next Question for the quiz.
     *
     * @param  mixed $quiz
     * @return Question
     */
    public function getNextQuestion(Quiz $quiz): Question
    {
        $openQuestion = $this->getOpenQuestion($quiz);
        if ($openQuestion) {
            return $openQuestion;
        }

        $questions = $this->getAvailableQuestions($quiz);
        $question = $questions->random();

        return $question;
    }

    /**
     * Get the questions that are not already attached to the quiz.
     *
     * @param  mixed $quiz
     * @return Collection
     */
    private function getAvailableQuestions(Quiz $quiz): Collection
    {
        $questionsIdsInCurrentQuiz = $quiz->questions->map->only(['id'])->flatten()->toArray();
        $questions = Question::whereNotIn('id', $questionsIdsInCurrentQuiz)->get();
        throw_if($questions->count() < 1, NoQuestionAvailableException::class, 'No further question found.');

        return $questions;
    }

    /**
     * Get an open question from the quiz.
     *
     * @param  mixed $quiz
     * @return Question
     */
    private function getOpenQuestion(Quiz $quiz): ?Question
    {
        return $quiz->questions->filter(function ($question) {
            return $question->quizPosition->answers->count() < 1;
        })->first();
    }

    /**
     * Add question to quiz if it is not already attached to it.
     *
     * @param  mixed $quiz
     * @param  mixed $question
     * @return void
     */
    public function addNewQuestion(Quiz $quiz, Question $question): void
    {
        if (!$quiz->questions->contains($question)) {
            $quiz->questions()->attach($question);
        }

        return;
    }

    public function getResult(Quiz $quiz): Collection
    {
        $result = [];
        $questions = $quiz->questions;
        foreach ($questions as $key => $question) {
            $result[$key + 1]['question'] = $question;
            $result[$key + 1]['correctAnswers'] = $question->answers->where('correct', true)->sortBy('identifier');
            $result[$key + 1]['choices'] = $question->quizPosition->answers->sortBy('identifier');
        }

        return collect($result);
    }
}