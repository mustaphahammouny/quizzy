<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PassedQuizItem extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'correct' => 'boolean',
        'chosen_answers' => 'array',
        'correct_answers' => 'array',
    ];

    public function passedQuiz(): BelongsTo
    {
        return $this->belongsTo(PassedQuiz::class);
    }
}
