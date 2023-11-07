<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Resources\QuizResource;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    public function index()
    {
        $quizzes = Quiz::with('teacher')
            ->where('user_id', Auth::id())
            ->paginate(9);

        return QuizResource::collection($quizzes);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Quiz $quiz)
    {
        //
    }

    public function edit(Quiz $quiz)
    {
        //
    }

    public function update(Request $request, Quiz $quiz)
    {
        //
    }

    public function destroy(Quiz $quiz)
    {
        //
    }
}
