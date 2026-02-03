<?php

namespace App\Exceptions\NotFoundException;

use Exception;

abstract class NotFoundException extends Exception
{
    public function __construct(string $message, int $code = 404)
    {
        parent::__construct($message, $code);
    }
}
