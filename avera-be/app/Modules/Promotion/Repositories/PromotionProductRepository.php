<?php

namespace App\Modules\Promotion\Repositories;

use App\Exceptions\ResourceNotFoundException;
use App\Modules\Promotion\Models\PromotionProduct;
use App\Traits\CacheVersionable;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class PromotionProductRepository
{
    use CacheVersionable;
    public function __construct(private PromotionProduct $model) {}
    public function get($perPage = 10): LengthAwarePaginator
    {
        $version = $this->versionKey('promotion_products');
        $page = request()->get('page', 1);
        return Cache::remember(
            "promotion_products:list:admin:page:{$page}v{$version}",
            now()->addMinutes(5),
            fn() => $this->model->orderBy('updated_at', 'DESC')->where('owner_type','admin')->paginate($perPage)
        );
    }
    public function find(string $id): ?PromotionProduct
    {
        $version = $this->versionKey('promotion_products');
        $promotion = Cache::remember("promotion_products:list:promotionId:{$id}:v{$version}", now()->addMinutes(1), fn() => $this->model->find($id));
        if (!$promotion) {
            throw new ResourceNotFoundException("PromotionProduct");
        }
        return $promotion;
    }
    public function store(array $data): PromotionProduct
    {
        return $this->model->create($data);
    }
    public function update(PromotionProduct $promotion, array $data): PromotionProduct
    {
        $promotion->update($data);
        return $promotion;
    }
    public function delete(PromotionProduct $promotion): bool
    {
        return $promotion->delete();
    }
}
