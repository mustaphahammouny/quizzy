<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAnswerRequest;
use App\Http\Requests\UpdateAnswerRequest;
use App\Http\Resources\Teacher\AnswerResource;
use App\Models\Answer;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class AnswerController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $answers = Answer::where('user_id', Auth::id())->get();

        return AnswerResource::collection($answers);
    }

    public function store(StoreAnswerRequest $request): AnswerResource
    {
        $answer = Answer::create($request->validated());

        return new AnswerResource($answer);
    }

    public function show(Answer $answer): AnswerResource
    {
        return new AnswerResource($answer);
    }

    public function update(UpdateAnswerRequest $request, Answer $answer): AnswerResource
    {
        $answer->update($request->validated());

        return new AnswerResource($answer);
    }

    public function destroy(Answer $answer): AnswerResource
    {
        $answer->delete();

        return new AnswerResource($answer);
    }
}
