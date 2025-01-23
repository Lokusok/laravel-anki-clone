<?php

namespace App\Services;

use App\Models\User;

class StatsService
{
    /**
     * @return array{decks_count: int, questions_count: int}
     */
    public function calculate(User $user)
    {
        $decksCount = $user->decks->count();
        $questionsCount = $user->decks->reduce(function ($acc, $deck) {
            return $acc + $deck->questions->count();
        }, 0);

        return [
            'decks_count' => $decksCount,
            'questions_count' => $questionsCount,
        ];
    }
}
