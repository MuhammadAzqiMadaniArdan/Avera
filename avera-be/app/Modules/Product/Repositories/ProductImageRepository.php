<?php

namespace App\Modules\Product\Repositories;

use App\Exceptions\ResourceNotFoundException;
use App\Modules\Product\Contracts\ProductImageRepositoryInterface;
use App\Modules\Product\Models\ProductImage;
use App\Traits\CacheVersionable;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class ProductImageRepository implements ProductImageRepositoryInterface
{

    use CacheVersionable;

    public function __construct(private ProductImage $model) {}

    public function get(int $perPage = 20): LengthAwarePaginator
    {
        $version = $this->versionKey('productImages');
        return Cache::remember("productImages:list:v{$version}", now()->addMinutes(5), fn() => $this->model->where('status', 'published')->orderBy('created_at', 'DESC')->paginate($perPage));
    }
    public function getByTrashed(int $perPage): LengthAwarePaginator
    {
        $version = $this->versionKey('productImages');
        return Cache::remember("productImages:list:by-trashed:v{$version}", now()->addMinutes(5), fn() => $this->model->onlyTrashed()->orderBy('updated_at', 'DESC')->paginate($perPage));
    }
    public function find(string $id): ?ProductImage
    {
        $version = $this->versionKey('productImages');
        $productImage = Cache::remember("productImage:list:productImageId:{$id}:v{$version}", now()->addMinutes(1), fn() => $this->model->find($id));
        if (!$productImage) {
            throw new ResourceNotFoundException('product image');
        }
        return $productImage;
    }
    public function findByProductId(string $id): ?Collection
    {
        $version = $this->versionKey('productImages');
        $productImage = Cache::remember("productImage:list:productId:{$id}:v{$version}", now()->addMinutes(1), fn() => $this->model->where('product_id', $id)->get());
        if (!$productImage) {
            throw new ResourceNotFoundException('product image');
        }
        return $productImage;
    }
     public function findByTrashed(string $id): ?ProductImage
    {
        $version = $this->versionKey('productImages');
        $productImage = Cache::remember("productImage:list:onlyTrashed:productImageId:{$id}:v{$version}", now()->addMinutes(1), fn() => $this->model->onlyTrashed()->find($id));
        if (!$productImage) {
            throw new ResourceNotFoundException('product image');
        }
        return $productImage;
    }
    public function store(array $data): ProductImage
    {
        return $this->model->create($data);
    }
    public function update(ProductImage $productImage, array $data): ProductImage
    {
        $productImage->update($data);
        return $productImage;
    }
    public function delete(ProductImage $productImage): bool
    {
        return $productImage->delete();
    }
    public function deletePermanent(ProductImage $productImage): bool
    {
        return $productImage->deletePermanent();
    }
    public function restore(ProductImage $productImage): bool
    {
        throw new $productImage->restore();
    }
}
