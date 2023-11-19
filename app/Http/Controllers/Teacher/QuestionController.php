<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQuestionRequest;
use App\Http\Requests\UpdateQuestionRequest;
use App\Http\Resources\Teacher\QuestionResource;
use App\Models\Question;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class QuestionController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $questions = Question::with('answers')
            ->where('user_id', Auth::id())
            ->get();

        return QuestionResource::collection($questions);
    }

    public function store(StoreQuestionRequest $request): QuestionResource
    {
        $question = Question::create($request->validated());

        return new QuestionResource($question);
    }

    public function show(Question $question): QuestionResource
    {
        return new QuestionResource($question);
    }

    public function update(UpdateQuestionRequest $request, Question $question): QuestionResource
    {
        $question->update($request->validated());

        return new QuestionResource($question);
    }

    public function destroy(Question $question): QuestionResource
    {
        $question->delete();

        return new QuestionResource($question);
    }
}
