<?php

namespace App\Exceptions;

use RuntimeException;

abstract class BusinessException extends RuntimeException
{
    protected int $statusCode = 400;

    public function statusCode() : int
    {
        return $this->statusCode;
    }
}
