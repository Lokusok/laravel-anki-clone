<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class ChangePasswordController
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', Rules\Password::default()],
        ]);

        $user = $request->user();

        if (! Hash::check($data['current_password'], $user->password)) {
            return abort(403, __('Введён неверный текущий пароль'));
        }

        $user->update([
            'password' => $data['new_password'],
        ]);

        return response()->noContent();
    }
}
