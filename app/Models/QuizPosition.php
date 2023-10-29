<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Collection;

class QuizPosition extends Pivot
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'quiz_position';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * @var array The fields that are fillable
     */
    protected $fillable = [
        'question_id',
        'quiz_id',
    ];

    /**
     * The answers that belong to the quiz position.
     */
    public function answers(): BelongsToMany
    {
        return $this->belongsToMany(Answer::class, 'choice', 'quiz_position_id')
            ->using(Choice::class)
            ->withTimestamps();
    }

    /**
     * Returns true if the solution to the question is correct.
     *
     * @return bool
     */
    public function isCorrect(): bool
    {
        if ($this->atLeastOneChoiceIsNotCorrect()) {
            return false;
        }

        if ($this->questionHasAtLeastOneMoreCorrectAnswerThatIsNoChoise()) {
            return false;
        }

        return true;
    }

    /**
     * Checks if at least one answer from the user choices is not correct.
     *
     * @param  mixed $answers
     * @return bool
     */
    private function atLeastOneChoiceIsNotCorrect(): bool
    {
        foreach ($this->answers as $answer) {
            if (!$answer->correct) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks if the question has at least one more correct answer than the user has chosen.
     *
     * @return bool
     */
    private function questionHasAtLeastOneMoreCorrectAnswerThatIsNoChoise(): bool
    {
        $question = Question::where('id', $this->question_id)->first();
        $correctAnswers = $question->answers->where('correct', true);
        $diff = $correctAnswers->diff($this->answers);
        if ($diff->count() > 0) {
            return true;
        }

        return false;
    }
}