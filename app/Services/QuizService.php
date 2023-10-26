<?php

namespace App\Services;

use App\Models\Question;
use App\Models\Quiz;

class QuizService
{

    public function getNextQuestion(Quiz $quiz): Question
    {
        $questionsIdsInCurrentQuiz = $quiz->questions->map->only(['id'])->flatten()->toArray();
        $question = Question::whereNotIn('id', $questionsIdsInCurrentQuiz)->get()->random();
        $quiz->questions()->attach($question->id);

        return $question;
    }
}