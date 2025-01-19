<?php

use App\Http\Controllers\Api\V1\AnswerController;
use App\Http\Controllers\Api\V1\DeckController;
use App\Http\Controllers\Api\V1\QuestionController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::controller(DeckController::class)->group(function () {
        Route::get('/decks', 'index')->name('decks.index');
        Route::post('/decks', 'store')->name('decks.store');
        Route::match(['PUT', 'PATCH'], '/decks/{deck}', 'update')->name('decks.update');
        Route::delete('/decks/{deck}', 'destroy')->name('decks.destroy');
    });

    Route::controller(QuestionController::class)->group(function () {
        Route::get('/decks/{deck}/questions', 'index')->name('questions.index');
        Route::post('/decks/{deck}/questions', 'store')->name('questions.store');
        Route::match(['PUT', 'PATCH'], '/decks/{deck}/questions/{questionId}', 'update')->name('questions.update');
        Route::delete('/decks/{deck}/questions/{questionId}', 'destroy')->name('questions.destroy');
    });

    Route::controller(AnswerController::class)->group(function () {
        Route::post('/decks/{deck}/questions/{question}/answer', 'store')->name('answers.store');
    });
});
