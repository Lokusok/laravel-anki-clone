<?php

namespace App\Http\Requests\Answers;

use App\Enums\AnswerType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreAnswerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => ['required', new Enum(AnswerType::class)]
        ];
    }

    public function messages(): array
    {
        return [
            'type.required' => 'Тип ответа обязателен',
            'type.Illuminate\Validation\Rules\Enum' => 'Неверный тип ответа'
        ];
    }
}
