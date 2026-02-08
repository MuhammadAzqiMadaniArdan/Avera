<?php

namespace App\Exceptions;

use Illuminate\Http\Response;

class CheckoutAlreadyProcessedException extends BusinessException
{
    protected int $statusCode = Response::HTTP_CONFLICT;

    public function __construct()
    {
        parent::__construct('Checkout already processed.');
    }
}
