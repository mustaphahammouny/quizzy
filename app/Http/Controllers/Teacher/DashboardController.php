<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function counts()
    {
        $quizzesCount = Quiz::where('user_id', Auth::id())
            ->count();

        return response()->json(['data' => [
            'quizzes_count' => $quizzesCount,
        ]]);
    }
}
