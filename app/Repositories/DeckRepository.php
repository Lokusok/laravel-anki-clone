<?php

namespace App\Repositories;

use App\Models\Deck;

class DeckRepository
{
    public function create(array $attributes): Deck
    {
        $deck = Deck::create([
            'title' => $attributes['title'],
            'user_id' => $attributes['user_id'],
        ]);

        return $deck;
    }
}
