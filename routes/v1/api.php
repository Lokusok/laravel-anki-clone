<?php

use App\Http\Controllers\Api\V1\DeckController;
use App\Http\Controllers\Api\V1\QuestionController;
use Illuminate\Support\Facades\Route;

Route::controller(DeckController::class)->group(function () {
    Route::get('/decks', 'index')->name('decks.index');
    Route::post('/decks', 'store')->name('decks.store');
});

Route::controller(QuestionController::class)->group(function () {
    Route::get('/decks/{deck}/questions', 'index')->name('questions.index');
    Route::post('/decks/{deck}/questions', 'store')->name('questions.store');
    Route::delete('/decks/{deck}/questions/{questionId}', 'destroy')->name('questions.destroy');
});
