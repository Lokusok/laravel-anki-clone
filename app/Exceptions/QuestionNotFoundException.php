<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

final class QuestionNotFoundException extends Exception
{
    public function render()
    {
        abort(Response::HTTP_NOT_FOUND, __('Вопрос с ID :id не существует', ['id' => $this->message]));
    }
}
