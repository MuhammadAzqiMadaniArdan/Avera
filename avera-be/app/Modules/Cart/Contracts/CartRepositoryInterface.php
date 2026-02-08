<?php

namespace App\Modules\Cart\Contracts;

use App\Modules\Cart\Models\CartItem;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface CartRepositoryInterface
{
    public function get(string $userId): Collection;
    public function find(string $id): ?CartItem;
    public function store(array $data): CartItem;
    public function update(CartItem $cartItem, array $data): CartItem;
    public function delete(CartItem $cartItem): bool;
}
