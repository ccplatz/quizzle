<?php

namespace App\Services;

use App\Models\Answer;
use App\Models\Question;
use Illuminate\Support\Arr;

class UpdateAnswersService
{
    /**
     * @var CreateAnswersService $createAnswersService
     */
    private CreateAnswersService $createAnswersService;

    /**
     * __construct
     *
     * @param  mixed $createAnswersService
     * @return void
     */
    public function __construct(CreateAnswersService $createAnswersService)
    {
        $this->createAnswersService = $createAnswersService;
    }

    /**
     * Update answer from request input.
     *
     * @param  mixed $input
     * @param  mixed $question
     * @return void
     */
    public function updateAnswersFromRequestInput(array $input, Question $question): void
    {
        foreach ($input['answers'] as $answerRawData) {
            if (empty($answerRawData['id']) && empty($answerRawData['text'])) {
                continue;
            }
            if (empty($answerRawData['id']) && !empty($answerRawData['text'])) {
                $this->createAnswersService->createAnswer($answerRawData, $question);
            }
            if (!empty($answerRawData['id']) && empty($answerRawData['text'])) {
                Answer::find($answerRawData['id'])->delete();
            }
            if (!empty($answerRawData['id']) && !empty($answerRawData['text'])) {
                $this->updateAnswer($answerRawData);
            }
        }
    }

    /**
     * Update Answer.
     *
     * @param  mixed $answerRawData
     * @return void
     */
    private function updateAnswer(array $answerRawData): void
    {
        $answer = Answer::find($answerRawData['id']);
        $answer->text = $answerRawData['text'];
        $answer->correct = Arr::has($answerRawData, 'correct') && $answerRawData['correct'] === 'on';
        $answer->update();
    }
}