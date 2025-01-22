<?php

namespace App\Http\Requests\Question;

use Illuminate\Foundation\Http\FormRequest;

class UpdateQuestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'front' => ['required', 'string'],
            'back' => ['required', 'string'],
            'deck_id' => ['integer', 'exists:decks,id'],
            'tags' => ['array'],
            'tags.*' => ['string'],
        ];
    }

    public function messages(): array
    {
        return [
            'front.required' => 'Вопрос обязателен',
            'front.string' => 'Вопрос должен быть строкой',
            'back.required' => 'Ответ обязателен',
            'back.string' => 'Ответ должен быть строкой',
            'deck_id.integer' => 'Неверный формат коллекции',
            'deck_id.exists' => 'Коллекции с указанным ID не существует',
            'tags.*' => 'Теги должны быть строкой',
        ];
    }
}
