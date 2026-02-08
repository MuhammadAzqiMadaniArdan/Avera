<?php

namespace App\Modules\Order\Contracts;

use App\Modules\Order\Models\Order;
use Illuminate\Pagination\LengthAwarePaginator;

interface OrderServiceInterface {
    public function find(string $id) : ?Order;
    public function store(string $checkoutId) : array;
}