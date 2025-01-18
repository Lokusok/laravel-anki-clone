<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDeckRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'unique:decks,title'],
            'user_id' => ['required', 'integer', 'exists:users,id']
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Название для группы является обязательным полем',
            'title.string' => 'Название для группы должно быть строкой',
            'title.unique' => 'Такая группа уже существует',
            'user_id.required' => 'Идентификатор пользователя является обязательным полем',
            'user_id.exists' => 'Такого пользователя не существует'
        ];
    }
}
