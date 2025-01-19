<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class QuestionToEarlyToAnswer extends Exception
{
    public function render()
    {
        abort(Response::HTTP_TOO_EARLY, $this->message);
    }
}
