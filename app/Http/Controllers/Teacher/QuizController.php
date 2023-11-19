<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQuizRequest;
use App\Http\Requests\UpdateQuizRequest;
use App\Http\Resources\Teacher\QuizResource;
use App\Models\Quiz;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $quizzes = Quiz::with('questions')
            ->where('user_id', Auth::id())
            ->get();

        return QuizResource::collection($quizzes);
    }

    public function store(StoreQuizRequest $request): QuizResource
    {
        $data = $request->validated();

        $data['user_id'] = Auth::id();

        $quiz = Quiz::create($data);

        return new QuizResource($quiz);
    }

    public function show(Quiz $quiz): QuizResource
    {
        return new QuizResource($quiz);
    }

    public function update(UpdateQuizRequest $request, Quiz $quiz): QuizResource
    {
        $quiz->update($request->validated());

        return new QuizResource($quiz);
    }

    public function destroy(Quiz $quiz): QuizResource
    {
        $quiz->delete();

        return new QuizResource($quiz);
    }
}
