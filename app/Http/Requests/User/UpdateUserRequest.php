<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'email' => ['required', 'string', 'email'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Имя пользователя обязательно',
            'name.string' => 'Имя пользователя должно быть строкой',
            'email.required' => 'Email обязателен',
            'email.string' => 'Email должен быть строкой',
            'email.email' => 'Необходим валидный email',
        ];
    }
}
