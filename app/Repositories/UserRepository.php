<?php

namespace App\Repositories;

use App\DTO\User\UserUpdateDTO;
use App\Models\User;

class UserRepository
{
    public function update(UserUpdateDTO $user): User
    {
        $foundedUser = User::find($user->id);

        $foundedUser->update([
            'name' => $user->name,
            'email' => $user->email,
        ]);

        return $foundedUser;
    }
}
