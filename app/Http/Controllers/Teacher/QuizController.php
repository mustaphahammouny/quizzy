<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQuizRequest;
use App\Http\Resources\QuizResource;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    public function index()
    {
        $quizzes = Quiz::where('user_id', Auth::id())->get();

        return QuizResource::collection($quizzes);
    }

    public function store(StoreQuizRequest $request)
    {
        $data = $request->validated();

        $data['user_id'] = Auth::id();

        $quiz = Quiz::create($data);

        return new QuizResource($quiz);
    }

    public function show(Quiz $quiz)
    {
        //
    }

    public function update(Request $request, Quiz $quiz)
    {
        //
    }

    public function destroy(Quiz $quiz)
    {
        $quiz->delete();

        return new QuizResource($quiz);
    }
}
