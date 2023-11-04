<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Choice extends Pivot
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'choices';

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
        'question_list_id',
        'answer_id',
    ];
}