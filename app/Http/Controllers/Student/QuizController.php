<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Resources\Student\QuizQuestionsResource;
use App\Http\Resources\Student\QuizResource;
use App\Models\Quiz;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    public function index(): AnonymousResourceCollection
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

    public function favorite(): AnonymousResourceCollection
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

    public function show(Quiz $quiz): QuizResource
    {
        $quiz->questions_count = $quiz->questions()->count();
        $quiz->questions_sum_time = $quiz->questions()->sum('time');
        $quiz->favorite_users_count = $quiz->favoriteUsers()->count();

        return new QuizResource($quiz);
    }

    public function toggleFavorite(Quiz $quiz): QuizResource
    {
        $user = Auth::user();

        $result = $user->favoriteQuizzes()->toggle($quiz);

        $quiz->favorite_users_exists = in_array($quiz->id, $result['attached']);

        return new QuizResource($quiz);
    }

    public function questions(Quiz $quiz): QuizQuestionsResource
    {
        return new QuizQuestionsResource($quiz);
    }
}
