<?php

use App\Http\Controllers\Api\V1\DeckController;
use Illuminate\Support\Facades\Route;

Route::get('/decks', [DeckController::class, 'index'])->name('decks.index');
Route::post('/decks', [DeckController::class, 'store'])->name('decks.store');
