<?php

namespace App\Exceptions;

class OrderStockIsNotEnough extends BusinessException
{
    protected int $statusCode = 422;

    public function __construct(int $stock)
    {
        return parent::__construct("Stock tinggal tersisa : $stock");
    }
}
