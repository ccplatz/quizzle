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
}