<?php

namespace App\Exceptions;

use Throwable;

class ForbiddenAccessException extends BusinessException {
    protected int $statusCode = 403;

    public function __construct()
    {
        return parent::__construct("Anda Tidak Memiliki Akses");
    }
}