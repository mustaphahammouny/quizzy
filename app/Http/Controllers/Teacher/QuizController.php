<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQuizRequest;
use App\Http\Requests\UpdateQuizRequest;
use App\Http\Resources\Teacher\QuizResource;
use App\Models\Quiz;
use App\Services\FileService;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
    public function __construct(protected FileService $fileService)
    {
    }

    public function index(): AnonymousResourceCollection
    {
        $quizzes = Quiz::where('user_id', Auth::id())
            ->get();

        return QuizResource::collection($quizzes);
    }

    public function store(StoreQuizRequest $request): QuizResource
    {
        $data = $request->safe()->except('image');

        $data['user_id'] = Auth::id();

        try {
            DB::beginTransaction();

            $quiz = Quiz::create($data);

            $this->fileService->put($quiz, $request->file('image'));

            DB::commit();

            return new QuizResource($quiz);
        } catch (Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }

    public function show(Quiz $quiz): QuizResource
    {
        $quiz->load('questions');

        return new QuizResource($quiz);
    }

    public function update(UpdateQuizRequest $request, Quiz $quiz): QuizResource
    {
        $data = $request->safe()->except('image');

        try {
            DB::beginTransaction();

            $quiz->update($data);

            $image = $request->file('image');
            $media = $this->fileService->get($quiz)->first();

            $exist = $this->fileService->exist($image, $media);

            if (!$exist) {
                $this->fileService->clearCollection($quiz);

                $this->fileService->put($quiz, $image);
            }

            DB::commit();

            return new QuizResource($quiz);
        } catch (Exception $e) {
            DB::rollBack();

            throw $e;
        }

        return new QuizResource($quiz);
    }

    public function destroy(Quiz $quiz): QuizResource
    {
        $quiz->delete();

        return new QuizResource($quiz);
    }
}
