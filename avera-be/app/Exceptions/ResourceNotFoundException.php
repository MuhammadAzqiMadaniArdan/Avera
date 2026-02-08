<?php

namespace App\Exceptions;

use Throwable;

class ResourceNotFoundException extends BusinessException {
    protected int $statusCode = 404;

    public function __construct(string $resource)
    {
        return parent::__construct("$resource tidak ditemukan");
    }
}