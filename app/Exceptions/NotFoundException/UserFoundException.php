<?php

namespace App\Exceptions\NotFoundException;

use App\Exceptions\NotFoundException\NotFoundException;

class UserFoundException extends NotFoundException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
