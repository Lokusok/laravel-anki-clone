<?php

namespace App\Repositories;

use App\Models\Deck;
use Illuminate\Database\Eloquent\Collection;

final class DeckRepository
{
    /**
     * @param array{title: string, user_id: int} $attributes
     */
    public function create(array $attributes): Deck
    {
        $deck = Deck::create([
            'title' => $attributes['title'],
            'user_id' => $attributes['user_id'],
        ]);

        return $deck;
    }

    /**
     * @param array{deck_id: string[]} $attributes
     */
    public function findById(array $attributes): Collection
    {
        $decks = Deck::query()->whereIn('id', $attributes['deck_id'])->get();

        return $decks;
    }
}
