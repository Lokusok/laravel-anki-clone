<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuestionRequest extends FormRequest
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
            'tags' => ['array'],
            'tags.*' => ['string']
        ];
    }

    public function messages(): array
    {
        return [
            'front.required' => 'Вопрос обязателен',
            'front.string' => 'Вопрос должен быть строкой',
            'back.required' => 'Ответ обязателен',
            'back.string' => 'Ответ должен быть строкой',
            'tags.*' => 'Теги должны быть строкой'
        ];
    }

    protected function passedValidation(): void
    {
        $this->merge([
            'when_ask' => now()->addDay()->format(\DateTime::ATOM),
        ]);
    }
}
