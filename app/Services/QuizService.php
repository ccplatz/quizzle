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
        if ($this->getOpenQuestion($quiz)) {
            return $this->getOpenQuestion($quiz);
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
}