<?php

namespace App\Exceptions;

use Exception;

class OrderException extends Exception
{
    public function __construct($message)
    {
        parent::__construct($message);
    }
}