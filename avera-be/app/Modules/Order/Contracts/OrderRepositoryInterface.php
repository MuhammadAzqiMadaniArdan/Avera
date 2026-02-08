<?php

namespace App\Modules\Order\Contracts;

use App\Modules\Order\Models\Order;
use Illuminate\Pagination\LengthAwarePaginator;

interface OrderRepositoryInterface {
    public function find(int $id) : ?Order;
    public function store(array $data) : Order;
}