<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class FavoriteQuiz extends Pivot
{
    use HasFactory;

    protected $table = 'favorite_quizzes';

    protected $guarded = [];
}
