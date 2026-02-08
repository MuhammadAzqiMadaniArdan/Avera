<?php

namespace App\Modules\Order\Repositories;

use App\Exceptions\ResourceNotFoundException;
use App\Modules\Order\Models\OrderItem;
use App\Modules\Product\Models\Product;
use App\Traits\CacheVersionable;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class OrderItemRepository 
{
    use CacheVersionable;

    public function __construct(private OrderItem $model) {}
    public function getUserOrder(string $id,array $filters): ?LengthAwarePaginator
    {
        $version = $this->versionKey('orders');
        return Cache::remember(
            "order:list:userId:{$id}:v{$version}",
            now()->addMinutes(5),
            fn() => $this->model->where('user_id', $id)->orderBy('updated_at', 'DESC')->paginate(5)
        );
    }

    public function find(string $id): ?OrderItem
    {
        $version = $this->versionKey('orders');
        return Cache::remember(
            "order:list:id:{$id}:v{$version}",
            now()->addMinutes(1),
            fn() => $this->model->find($id)
        );
    }
    public function findOrFail(string $id): ?OrderItem
    {
        $orderItem = $this->model->with(['product','review'])->find($id);
        if(!$orderItem) throw new ResourceNotFoundException("order item");
        return $orderItem;
    }
    public function store(array $data): OrderItem
    {
        return $this->model->create($data);
    }
    public function deductStock(OrderItem $orderItem, int $quantity): Product
    {
        $product = $orderItem->product;
        $product->update(['stock' => $product->stock - $quantity]);
        return $product;
    }
}
