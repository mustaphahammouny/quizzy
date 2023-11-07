<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Resources\QuizResource;
use App\Models\Quiz;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function index()
    {
        $quizzes = Quiz::with('teacher')
            ->where('active', true)
            ->paginate(9);

        return QuizResource::collection($quizzes);
    }

    public function store(Request $request)
    {
        //
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
        //
    }
}
