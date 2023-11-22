<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function counts()
    {
        $quizzesCount = Quiz::where('user_id', Auth::id())
            ->count();

        $studentsCount = User::whereHas('passedQuizzes.quiz', function ($query) {
            $query->where('user_id', Auth::id());
        })
            ->count();

        return response()->json(['data' => [
            'quizzes_count' => $quizzesCount,
            'students_count' => $studentsCount,
        ]]);
    }
}
