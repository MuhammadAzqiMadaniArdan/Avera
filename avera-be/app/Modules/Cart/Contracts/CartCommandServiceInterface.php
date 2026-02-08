<?php

namespace App\Modules\Cart\Contracts;

use App\Modules\Cart\Models\CartItem;

interface CartCommandServiceInterface
{
    public function store(array $data, string $userId): CartItem;
    public function update(string $id, array $data): CartItem;
    public function delete(string $id): bool;
    public function flush(string $userId): bool;
}
