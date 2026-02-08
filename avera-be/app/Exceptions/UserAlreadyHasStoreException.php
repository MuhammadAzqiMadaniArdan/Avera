<?php

namespace App\Exceptions;

use Throwable;

class UserAlreadyHasStoreException extends BusinessException {
    protected int $statusCode = 422;

    public function __construct()
    {
        return parent::__construct("User sudah memiliki store,tidak dapat membuat store lagi !");
    }
}