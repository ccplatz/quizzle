<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Quiz extends Model
{
    use HasFactory;

    /**
     * @var array The fields that are fillable
     */
    protected $fillable = [
        'number_of_questions',
        'user_id'
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['questions.answers'];

    /**
     * Get the user that owns the quiz.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The questions that belong to the quiz.
     */
    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(Question::class, 'quiz_position')
            ->using(QuizPosition::class)
            ->as('quizPosition')
            ->withPivot('id')
            ->withTimestamps();
    }

    /**
     * Returns true if the quiz is finished.
     *
     * @return bool
     */
    public function isFinished(): bool
    {
        return $this->questions->count() >= $this->number_of_questions;
    }

    /**
     * Returns true if the quiz is finished.
     *
     * @return bool
     */
    public function getCountOfOpenQuizPositions(): int
    {
        return $this->number_of_questions - $this->questions->count() + 1;
    }

    public function getCountOfCorrectQuizPositions(): int
    {
        $counter = 0;
        foreach ($this->questions as $question) {
            if ($question->quizPosition->isCorrect()) {
                $counter++;
            }
        }

        return $counter;
    }
}