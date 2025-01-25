<?php

namespace App\Http\Requests\Deck;

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
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Название для группы является обязательным полем',
            'title.string' => 'Название для группы должно быть строкой',
            'title.unique' => 'Такая группа уже существует',
        ];
    }
}
