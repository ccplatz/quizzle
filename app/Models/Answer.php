<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Answer extends Model
{
    use HasFactory;

    /**
     * The attributes that should be fillable.
     *
     * @var array
     */
    protected $fillable = [
        'text',
        'question_id',
        'correct',
        'identifier'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'correct' => 'boolean'
    ];

    /**
     * Get the question that owns the answer.
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * The quiz positions that belong to the answer.
     */
    public function quizPositions(): BelongsToMany
    {
        return $this->belongsToMany(QuizPosition::class, 'choices')
            ->using(Choice::class)
            ->withTimestamps();
    }
}