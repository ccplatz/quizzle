<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\Pivot;

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
        return $this->belongsToMany(Answer::class)->using(Choice::class);
    }
}