<?php

namespace App\Services;

use App\Models\Question;
use App\Models\Quiz;

class QuizService
{

    public function getNextQuestion(Quiz $quiz): Question
    {
        $questionsInCurrentQuiz = $quiz->questions;
        $question = Question::whereNotIn('id', $questionsInCurrentQuiz)->get()->random();
        $quiz->questions()->attach($question->id);

        return $question;
    }
}