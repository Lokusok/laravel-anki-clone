<?php

namespace App\Repositories;

use App\Models\Deck;

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
}
