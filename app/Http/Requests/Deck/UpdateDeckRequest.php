<?php

namespace App\Http\Requests\Deck;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDeckRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Заголовок является обязательным полем',
            'title.string' => 'Заголовок должен быть строкой',
        ];
    }
}
