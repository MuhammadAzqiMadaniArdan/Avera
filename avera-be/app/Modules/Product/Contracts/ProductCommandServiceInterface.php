<?php

namespace App\Modules\Product\Contracts;

use App\Modules\Product\Models\Product;

interface ProductCommandServiceInterface
{
    public function store(array $data): Product;
    public function update(
        string $id,
        array $data,
        array $imageIds
    ): Product;
    public function publish(
        string $id,
        array $data,
        array $imageIds
    ): Product;
    public function inactive(string $id): Product;
    public function delete(string $id): bool;
    public function deletePermanent(string $id): bool;
    public function restore(string $id): bool;
    public function recalculateByImageId(string $imageId): void;
}
