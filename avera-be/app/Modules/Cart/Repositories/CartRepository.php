<?php

namespace App\Modules\Cart\Repositories;

use App\Exceptions\ResourceNotFoundException;
use App\Modules\Cart\Contracts\CartRepositoryInterface;
use App\Modules\Cart\Models\CartItem;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class CartRepository implements CartRepositoryInterface
{
    public function __construct(
        private CartItem $model
    ) {}
    public function get(string $userId): Collection
    {
        return  $this->model->query()
            ->where('user_id', $userId)
            ->with(['product.store','product.primaryImage'])
            ->orderBy('updated_at', 'DESC')
            ->get();
    }
    public function find(string $id): ?CartItem
    {
        $cartItem = $this->model->find($id);
        return $cartItem;
    }
    public function findOrFail(string $id): ?CartItem
    {
        $cartItem = $this->model->with('product')->find($id);
        if (!$cartItem) {
            throw new ResourceNotFoundException("Cart item");
        }
        return $cartItem;
    }
    public function findByProduct(string $productId): ?CartItem
    {
        $cartItem = $this->model->where('product_id',$productId)->first();
        if (!$cartItem) {
            throw new ResourceNotFoundException("Cart item");
        }
        return $cartItem;
    }
    public function findByUserAndProduct(string $userId, string $productId): ?CartItem
    {
        $cartItem =  $this->model->where('user_id', $userId)->where('product_id', $productId)->first();
        return $cartItem;
    }
    public function store(array $data): CartItem
    {
        return $this->model->create($data);
    }
    public function update(CartItem $cartItem, array $data): CartItem
    {
        $cartItem->update($data);
        return $cartItem;
    }
    public function delete(CartItem $cartItem): bool
    {
        return $cartItem->delete();
    }
    public function flush(string $userId): bool
    {
        return $this->model->where('user_id', $userId)->delete();
    }
}
