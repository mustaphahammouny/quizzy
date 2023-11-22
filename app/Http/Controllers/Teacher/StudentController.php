<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Resources\Teacher\StudentResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $students = User::with('passedQuizzes.quiz')
            ->whereHas('passedQuizzes.quiz', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->get();

        return StudentResource::collection($students);
    }
}
