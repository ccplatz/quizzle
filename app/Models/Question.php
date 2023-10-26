<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The identifier of the first answer on a question.
     * 
     * @var 
     */
    public const FIRST_ANSWER = 'A';

    /**
     * The identifier of the last answer on a question.
     * 
     * @var 
     */
    public const LAST_ANSWER = 'J';


    protected $fillable = [
        'text',
        'user_id'
    ];

    /**
     * Get the user that owns the question.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the answers for the question
     */
    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }

    /**
     * The quizzes that belong to the question.
     */
    public function quizzes(): BelongsToMany
    {
        return $this->belongsToMany(Quiz::class, 'quiz_position')
            ->using(QuizPosition::class)
            ->withTimestamps();
    }
}