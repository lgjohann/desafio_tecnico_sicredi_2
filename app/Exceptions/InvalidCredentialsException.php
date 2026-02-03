<?php

namespace App\Exceptions;

use Exception;

class InvalidCredentialsException extends Exception
{
    public function __construct($message, $status = 401)
    {
        parent::__construct($message, $status);
    }
}
