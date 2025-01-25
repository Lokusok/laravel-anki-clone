<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class DeckMustUniqueForUserException extends Exception
{
    public function render()
    {
        abort(Response::HTTP_UNPROCESSABLE_ENTITY, __('Такая коллекция уже существует'));
    }
}
