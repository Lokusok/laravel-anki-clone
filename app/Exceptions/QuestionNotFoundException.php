<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

final class QuestionNotFoundException extends Exception
{
    public function render()
    {
        abort(Response::HTTP_NOT_FOUND, $this->message);
    }
}
