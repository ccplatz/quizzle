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
}