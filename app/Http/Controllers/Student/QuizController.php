<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Resources\Student\QuizResource;
use App\Models\Quiz;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    public function index()
    {
        $quizzes = Quiz::with('user')
            ->withCount('questions')
            ->withSum('questions', 'time')
            ->withExists(['favoriteUsers' => function ($query) {
                $query->where('user_id', Auth::id());
            }])
            ->where('active', true)
            ->paginate(12);

        return QuizResource::collection($quizzes);
    }

    public function favorite()
    {
        $quizzes = Quiz::with('user')
            ->withCount('questions')
            ->withSum('questions', 'time')
            ->withExists(['favoriteUsers' => function ($query) {
                $query->where('user_id', Auth::id());
            }])
            ->whereHas('favoriteUsers', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->where('active', true)
            ->paginate(12);

        return QuizResource::collection($quizzes);
    }

    public function show(Quiz $quiz)
    {
        $quiz->questions_count = $quiz->questions()->count();
        $quiz->questions_sum_time = $quiz->questions()->sum('time');
        $quiz->favorite_users_count = $quiz->favoriteUsers()->count();
        $quiz->user = $quiz->user;

        return new QuizResource($quiz);
    }

    public function toggleFavorite(Quiz $quiz)
    {
        $user = Auth::user();

        $result = $user->favoriteQuizzes()->toggle($quiz);

        $quiz->favorite_users_exists = in_array($quiz->id, $result['attached']);

        return new QuizResource($quiz);
    }
}
