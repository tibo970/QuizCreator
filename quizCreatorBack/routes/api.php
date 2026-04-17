<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\QuizController;
use App\Http\Controllers\Api\ScoreController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/quizzes', [QuizController::class, 'index']);
Route::get('/quizzes/{id}', [QuizController::class, 'show']);
Route::get('/quizzes/{id}/scores', [ScoreController::class, 'bestScores']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    Route::get('/my-quizzes', [QuizController::class, 'myQuizzes']);
    Route::post('/quizzes', [QuizController::class, 'store']);
    Route::delete('/quizzes/{id}', [QuizController::class, 'destroy']);
    Route::post('/scores', [ScoreController::class, 'store']);
});
