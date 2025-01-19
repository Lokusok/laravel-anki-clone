<?php

namespace App\Policies;

use App\Models\Deck;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DeckPolicy
{
    public function isUserHasIt(User $user, Deck $deck): Response
    {
        return $deck->user_id === $user->id
            ? Response::allow()
            : Response::denyWithStatus(404, 'Такой коллекции не существует');
    }

    public function viewQuestions(User $user, Deck $deck): Response
    {
        return $this->isUserHasIt($user, $deck);
    }

    public function storeQuestion(User $user, Deck $deck): Response
    {
        return $this->isUserHasIt($user, $deck);
    }

    public function updateQuestion(User $user, Deck $deck): Response
    {
        return $this->isUserHasIt($user, $deck);
    }

    public function deleteQuestion(User $user, Deck $deck): Response
    {
        return $this->isUserHasIt($user, $deck);
    }

    public function update(User $user, Deck $deck): Response
    {
        return $this->isUserHasIt($user, $deck);
    }

    public function delete(User $user, Deck $deck): Response
    {
        return $this->isUserHasIt($user, $deck);
    }

    public function answer(User $user, Deck $deck): Response
    {
        return $this->isUserHasIt($user, $deck);
    }
}
