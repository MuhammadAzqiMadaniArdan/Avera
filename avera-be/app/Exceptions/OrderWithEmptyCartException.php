<?php

namespace App\Exceptions;


class OrderWithEmptyCartException extends BusinessException
{
    protected int $statusCode = 422;

    public function __construct()
    {
        return parent::__construct("Cart Tidak boleh kosong");
    }
}
