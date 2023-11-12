<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAnswerRequest;
use App\Http\Requests\UpdateAnswerRequest;
use App\Http\Resources\AnswerResource;
use App\Models\Answer;
use Illuminate\Support\Facades\Auth;

class AnswerController extends Controller
{
    public function index()
    {
        $answers = Answer::where('user_id', Auth::id())->get();

        return AnswerResource::collection($answers);
    }

    public function store(StoreAnswerRequest $request)
    {
        $answer = Answer::create($request->validated());

        return new AnswerResource($answer);
    }

    public function show(Answer $answer)
    {
        return new AnswerResource($answer);
    }

    public function update(UpdateAnswerRequest $request, Answer $answer)
    {
        $answer->update($request->validated());

        return new AnswerResource($answer);
    }

    public function destroy(Answer $answer)
    {
        $answer->delete();

        return new AnswerResource($answer);
    }
}
