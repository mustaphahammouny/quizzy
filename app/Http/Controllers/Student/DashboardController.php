<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\PassedQuiz;
use App\Models\Quiz;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function counts()
    {
        $quizzesCount = Quiz::where('active', true)
            ->count();

        $passedQuizzesCount = PassedQuiz::where('user_id', Auth::id())
            ->count();

        $favoriteQuizzesCount = Quiz::whereHas('favoriteUsers', function ($query) {
            $query->where('user_id', Auth::id());
        })
            ->where('active', true)
            ->count();

        return response()->json(['data' => [
            'quizzes_count' => $quizzesCount,
            'passed_quizzes_count' => $passedQuizzesCount,
            'favorite_quizzes_count' => $favoriteQuizzesCount,
        ]]);
    }
}
