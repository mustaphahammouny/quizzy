<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePassedQuizRequest;
use App\Http\Resources\Student\PassedQuizResource;
use App\Models\PassedQuiz;
use App\Models\Question;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PassedQuizController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $passedQuizzes = PassedQuiz::query()
            ->with(['quiz.user', 'items'])
            ->withCount([
                'items',
                'items as correct_items_count' => fn($query) => $query->where('correct', true),
            ])
            ->withSum('items', 'time')
            ->where('user_id', Auth::id())
            ->paginate(12);

        return PassedQuizResource::collection($passedQuizzes);
    }

    public function show(PassedQuiz $passedQuiz): PassedQuizResource
    {
        return new PassedQuizResource($passedQuiz);
    }

    public function store(StorePassedQuizRequest $request)
    {
        $data = $request->validated();

        $questions = Question::with('answers')
            ->where('quiz_id', $data['quiz_id'])
            ->get()
            ->keyBy('id');

        $items = [];

        foreach ($data['answers'] as $questionId => $chosenAnswerIds) {
            $question = $questions->get($questionId);
            $answers = $question->answers->keyBy('id');
            $correctAnswers = $question->answers->where('correct', true);

            $chosenAnswerIds = is_array($chosenAnswerIds) ? $chosenAnswerIds : [$chosenAnswerIds];
            $chosenAnswers = [];

            foreach ($chosenAnswerIds as $chosenAnswerId) {
                $chosenAnswers[] = $answers->get($chosenAnswerId)->answer;
            }

            $correct = empty(array_diff($correctAnswers->pluck('id')->toArray(), $chosenAnswerIds));

            $items[] = [
                'question' => $question->question,
                'time' => $question->time,
                'correct' => $correct,
                'chosen_answers' => $chosenAnswers,
                'correct_answers' => $correctAnswers->pluck('answer')->toArray(),
            ];
        }

        try {
            DB::beginTransaction();

            PassedQuiz::where('quiz_id', $data['quiz_id'])
                ->where('user_id', Auth::id())
                ->delete();

            $passedQuiz = PassedQuiz::create([
                'quiz_id' => $data['quiz_id'],
                'user_id' => Auth::id(),
            ]);

            $passedQuiz->items()->createMany($items);

            DB::commit();

            $passedQuiz->load('quiz.user')
                ->loadCount([
                    'items',
                    'items as correct_items_count' => fn($query) => $query->where('correct', true),
                ])
                ->loadSum('items', 'time');

            return new PassedQuizResource($passedQuiz);
        } catch (Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }
}
