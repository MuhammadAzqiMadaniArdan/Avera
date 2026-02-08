<?php

namespace App\Modules\Product\Contracts;

use App\Modules\Product\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ProductRepositoryInterface
{
    public function get(array $filters): LengthAwarePaginator;
    public function getByTrashed(int $perPage): LengthAwarePaginator;
    public function find(string $id): ?Product;
    public function findByImageId(string $imageId): Collection;
    public function findByTrashed(string $id): ?Product;
    public function store(array $data): Product;
    public function update(Product $product, array $data): Product;
    public function delete(Product $product): bool;
    public function deletePermanent(Product $product): bool;
    public function restore(Product $product): bool;
}
