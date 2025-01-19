<?php

namespace App\Policies;

use App\Models\Deck;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DeckPolicy
{
    public function viewQuestions(User $user, Deck $deck): bool
    {
        return $deck->user_id === $user->id;
    }

    public function storeQuestion(User $user, Deck $deck): bool
    {
        return $deck->user_id === $user->id;
    }

    public function updateQuestion(User $user, Deck $deck): bool
    {
        return $deck->user_id === $user->id;
    }

    public function update(User $user, Deck $deck): bool
    {
        return $deck->user_id === $user->id;
    }

    public function delete(User $user, Deck $deck): bool
    {
        return $deck->user_id === $user->id;
    }

    public function answer(User $user, Deck $deck): bool
    {
        return $deck->user_id === $user->id;
    }
}
