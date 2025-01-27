<?php

namespace App\Http\Controllers\Api\V1;

use App\DTO\User\UserUpdateDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function update(UpdateUserRequest $request, UserRepository $repository)
    {
        /**
         * @var App\Models\User
         */
        $user = Auth::user();

        $data = $request->validated();

        $updatedUser = $repository->update(new UserUpdateDTO(
            $user->id,
            $data['name'],
            $data['email'],
        ));

        return UserResource::make($updatedUser);
    }
}
