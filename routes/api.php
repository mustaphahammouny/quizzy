<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Student\QuizController as StudentQuizController;
use App\Http\Controllers\Teacher\QuizController as TeacherQuizController;
use App\Http\Controllers\Teacher\QuestionController as TeacherQuestionController;
use App\Http\Controllers\Teacher\AnswerController as TeacherAnswerController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', [AuthController::class, 'user']);

    Route::middleware('role:teacher')->prefix('teacher')->group(function () {
        Route::apiResource('quizzes', TeacherQuizController::class);

        Route::apiResource('questions', TeacherQuestionController::class)->except(['index']);

        Route::apiResource('answers', TeacherAnswerController::class)->except(['index']);
    });

    Route::middleware('role:student')->prefix('student')->group(function () {
        Route::get('quizzes', [StudentQuizController::class, 'index']);
        Route::get('quizzes/{quiz}/favorite', [StudentQuizController::class, 'toggleFavorite']);
        Route::get('quizzes/favorite', [StudentQuizController::class, 'favorite']);
        Route::get('quizzes/{quiz}', [StudentQuizController::class, 'show']);
    });
});
