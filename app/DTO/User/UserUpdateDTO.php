<?php

namespace App\DTO\User;

class UserUpdateDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public string $email
    ) {}
}
