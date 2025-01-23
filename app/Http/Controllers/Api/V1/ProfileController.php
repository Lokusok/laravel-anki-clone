<?php

namespace App\Http\Controllers\Api\V1;

use App\DTO\User\UserUpdateDTO;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function update(Request $request, UserRepository $repository)
    {
        /**
         * @var App\Models\User
         */
        $user = Auth::user();

        $repository->update(new UserUpdateDTO(
            $user->id,
            $request->input('name', $user->name),
            $request->input('email', $user->email)
        ));

        return response()->json([
            'success' => true
        ], 200);
    }
}
