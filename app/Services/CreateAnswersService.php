<?php

namespace App\Services;

use App\Models\Answer;
use App\Models\Question;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class CreateAnswersService
{
    /**
     * Create answers from request input.
     *
     * @param  mixed $input
     * @param  mixed $question
     * @return Collection
     */
    public function createAnswersFromRequestInput(array $input, Question $question): void
    {
        foreach ($input['answers'] as $answerRawData) {
            if (empty($answerRawData['text'])) {
                continue;
            }
            $this->createAnswer($answerRawData, $question);
        }
    }

    /**
     * Create an answer.
     *
     * @param  mixed $answerRawData
     * @param  mixed $question
     * @return Answer
     */
    public function createAnswer(array $answerRawData, Question $question): void
    {
        $answer = new Answer();
        $answer->text = $answerRawData['text'];
        $answer->question_id = $question->id;
        $answer->correct = Arr::has($answerRawData, 'correct') && $answerRawData['correct'] === 'on';
        $answer->identifier = $answerRawData['identifier'];
        $answer->save();
    }
}