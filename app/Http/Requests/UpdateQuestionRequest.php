<?php

namespace App\Http\Requests;

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
        ];
    }

    public function messages(): array
    {
        return [
            'front.required' => 'Вопрос обязателен',
            'front.string' => 'Вопрос должен быть строкой',
            'back.required' => 'Ответ обязателен',
            'back.string' => 'Ответ должен быть строкой',
        ];
    }
}
