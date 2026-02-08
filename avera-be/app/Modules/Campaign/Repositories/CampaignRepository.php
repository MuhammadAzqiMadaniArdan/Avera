<?php

namespace App\Modules\Promotion\Repositories;

use App\Exceptions\ResourceNotFoundException;
use App\Modules\Promotion\Models\Promotion;
use App\Traits\CacheVersionable;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class CampaignRepository implements CampaignRepositoryInterface
{
    use CacheVersionable;
    public function __construct(private Promotion $model) {}
    public function get($perPage = 10): LengthAwarePaginator
    {
        $version = $this->versionKey('promotions');
        $page = request()->get('page', 1);
        return Cache::remember(
            "promotions:list:admin:page:{$page}v{$version}",
            now()->addMinutes(5),
            fn() => $this->model->orderBy('updated_at', 'DESC')->where('owner_type','admin')->paginate($perPage)
        );
    }
    public function getAllStore($perPage = 10): LengthAwarePaginator
    {
        $version = $this->versionKey('promotions');
        $page = request()->get('page', 1);
        return Cache::remember(
            "promotions:list:store:page:{$page}:v{$version}",
            now()->addMinutes(5),
            fn() => $this->model->where('owner_type', 'store')->orderBy('updated_at', 'DESC')->paginate($perPage)
        );
    }
    public function getStore(string $storeId): LengthAwarePaginator
    {
        $perPage = 10;
        $version = $this->versionKey('promotions');
        return Cache::remember(
            "promotions:list:storeId:{$storeId}v{$version}",
            now()->addMinutes(5),
            fn() => $this->model->where('owner_type', 'store')->where('store_id', $storeId)->orderBy('updated_at', 'DESC')->paginate($perPage)
        );
    }
    public function find(string $id): ?Promotion
    {
        $version = $this->versionKey('promotions');
        $promotion = Cache::remember("promotions:list:promotionId:{$id}:v{$version}", now()->addMinutes(1), fn() => $this->model->find($id));
        if (!$promotion) {
            throw new ResourceNotFoundException("Promotion");
        }
        return $promotion;
    }

    public function findBySlug(string $slug): ?Promotion
    {
        $version = $this->versionKey('promotions');
        $promotion = Cache::remember(
            "promotions:list:slug:{$slug}:v{$version}",
            now()->addMinutes(1),
            fn() => $this->model->where('slug', $slug)->with('vouchers')->first()
        );
        if (!$promotion) {
            throw new ResourceNotFoundException("Promotion");
        }
        return $promotion;
    }
    public function store(array $data): Promotion
    {
        return $this->model->create($data);
    }
    public function update(Promotion $promotion, array $data): Promotion
    {
        $promotion->update($data);
        return $promotion;
    }
    public function delete(Promotion $promotion): bool
    {
        return $promotion->delete();
    }
    public function deletePermanent(Promotion $promotion): bool
    {
        return $promotion->deletePermanent();
    }
    public function restore(Promotion $promotion): bool
    {
        return $promotion->restore();
    }
    public function getByTrashed($perPage = 10): LengthAwarePaginator
    {
        $version = $this->versionKey('promotions');
        return Cache::remember(
            "promotions:list:onlyTrashed:v{$version}",
            now()->addMinutes(5),
            fn() => $this->model->onlyTrashed()->orderBy('updated_at', 'DESC')->paginate($perPage)
        );
    }
    public function findByTrashed(string $id): ?Promotion
    {
        $version = $this->versionKey('promotions');
        $promotion = Cache::remember("promotions:list:promotionId:{$id}:byTrashed:v{$version}", now()->addMinutes(1), fn() => $this->model->onlyTrashed()->find($id));
        if (!$promotion) {
            throw new ResourceNotFoundException("Promotion");
        }
        return $promotion;
    }
}
