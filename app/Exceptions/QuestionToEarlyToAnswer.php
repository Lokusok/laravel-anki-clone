<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

final class QuestionToEarlyToAnswer extends Exception
{
    public function render()
    {
        abort(Response::HTTP_TOO_EARLY, $this->message);
    }
}
