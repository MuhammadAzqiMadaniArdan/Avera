<?php

namespace App\Exceptions;

use Illuminate\Http\Response;

class CheckoutItemEmptyException extends BusinessException
{
    protected int $statusCode = Response::HTTP_UNPROCESSABLE_ENTITY;

    public function __construct()
    {
        parent::__construct('Items checkout belum ada.');
    }
}
