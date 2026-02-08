<?php

namespace App\Modules\Cart\Contracts;

use App\Modules\Cart\Models\CartItem;
use Illuminate\Pagination\LengthAwarePaginator;

interface CartQueryServiceInterface
{
    public function get(string $userId);
    public function find(string $id): ?CartItem;
}
