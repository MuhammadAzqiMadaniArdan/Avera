<?php

namespace App\Modules\Product\Contracts;

use App\Modules\Product\Models\ProductImage;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ProductImageRepositoryInterface
{
    public function get(int $id): LengthAwarePaginator;
    public function getByTrashed(int $perPage): LengthAwarePaginator;
    public function find(string $id): ?ProductImage;
    public function findByTrashed(string $id): ?ProductImage;
    public function findByProductId(string $id): ?Collection;
    public function store(array $data): ProductImage;
    public function update(ProductImage $productImage, array $data): ProductImage;
    public function delete(ProductImage $productImage): bool;
    public function deletePermanent(ProductImage $productImage): bool;
    public function restore(ProductImage $productImage): bool;
}
