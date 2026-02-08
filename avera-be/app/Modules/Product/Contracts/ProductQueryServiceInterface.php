<?php

namespace App\Modules\Product\Contracts;

use App\Modules\Product\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ProductQueryServiceInterface
{
    public function filter(array $filters): LengthAwarePaginator;
    public function getProductByCategory(
        string $slug,
        array $filters
    ): LengthAwarePaginator;
    public function getProductByStoreSlug(
        string $slug,
        array $filters
    ): LengthAwarePaginator;
    public function getProductSeller(
        array $filters
    ): LengthAwarePaginator;
    public function getRandomProduct(): Collection;
    public function getRandomDiscovery(): LengthAwarePaginator;
    public function find(string $compound): ?Product;
    public function findById(string $id): ?Product;
}
