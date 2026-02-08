<?php

namespace App\Modules\Promotion\Services;

use App\Modules\Promotion\Models\PromotionProduct;
use App\Modules\Promotion\Repositories\PromotionProductRepository;
use App\Modules\User\Repositories\UserRepository;
use App\Traits\CacheVersionable;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Gate;

class PromotionProductService
{
    use CacheVersionable;
    public function __construct(private PromotionProductRepository $promotionProductRepository, private UserRepository $userRepository) {}
    public function get(int $perPage): LengthAwarePaginator
    {
        return $this->promotionProductRepository->get($perPage);
    }
    public function find(string $id): ?PromotionProduct
    {
        return $this->promotionProductRepository->find($id);
    }
    public function store(array $products, string $promotionId): void
    {
        foreach ($products as $item) {
        foreach ($item['ids'] as $productId) {
            $this->promotionProductRepository->store([
                'promotion_id' => $promotionId,
                'product_id' => $productId,
                'discount_type' => $item['discount_type'],
                'discount_value' => $item['discount_value'],
            ]);
        }
    }
        $this->invalidateCache('promotion_products');
    }
    public function update(string $id, array $data): PromotionProduct
    {
        $promotion = $this->promotionProductRepository->find($id);
        Gate::authorize('update',$promotion);
        $result = $this->promotionProductRepository->update($promotion, $data);
        $this->invalidateCache('promotion_products');
        return $result;
    }
    public function delete(string $id): bool
    {
        $promotion = $this->promotionProductRepository->find($id);
        if ($promotion->owner_type === 'admin') throw new Exception('Anda tidak dapat menghapus promo admin !');
        return $this->promotionProductRepository->delete($promotion);
    }
}
