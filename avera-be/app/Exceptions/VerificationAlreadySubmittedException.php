<?php

namespace App\Exceptions;

use Throwable;

class VerificationAlreadySubmittedException extends BusinessException {
    protected int $statusCode = 422;

    public function __construct()
    {
        return parent::__construct("Verifikasi sudah pernah disubmit harap menunggu!");
    }
}