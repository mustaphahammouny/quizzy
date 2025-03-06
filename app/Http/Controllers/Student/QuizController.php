<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Resources\Student\QuizQuestionsResource;
use App\Http\Resources\Student\QuizResource;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $quizzes = Quiz::with('user')
            ->withCount('questions')
            ->withCount('favoriteUsers')
            ->withSum('questions', 'time')
            ->withExists([
                'favoriteUsers' => fn($query) => $query->where('user_id', Auth::id()),
            ])
            ->where('active', true)
            ->paginate(12);

        return QuizResource::collection($quizzes);
    }

    public function favorite(): AnonymousResourceCollection
    {
        $quizzes = Quiz::with('user')
            ->withCount('questions')
            ->withCount('favoriteUsers')
            ->withSum('questions', 'time')
            ->withExists([
                'favoriteUsers' => fn($query) => $query->where('user_id', Auth::id())
            ])
            ->whereHas('favoriteUsers', fn($query) => $query->where('user_id', Auth::id()))
            ->where('active', true)
            ->paginate(12);

        return QuizResource::collection($quizzes);
    }

    public function show(Quiz $quiz): QuizResource
    {
        $quiz->loadCount('questions');
        $quiz->loadSum('questions', 'time');
        $quiz->loadCount('favoriteUsers');

        return QuizResource::make($quiz);
    }

    public function toggleFavorite(Quiz $quiz): QuizResource
    {
        /** @var User */
        $user = Auth::user();

        $result = $user->favoriteQuizzes()->toggle($quiz);

        $quiz->favorite_users_exists = in_array($quiz->id, $result['attached']);

        return QuizResource::make($quiz);
    }

    public function questions(Quiz $quiz): QuizQuestionsResource
    {
        $quiz->load('questions.answers');

        return QuizQuestionsResource::make($quiz);
    }
}
