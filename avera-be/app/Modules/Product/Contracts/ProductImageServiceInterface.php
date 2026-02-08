<?php

namespace App\Modules\Product\Contracts;

use App\Modules\Product\Models\ProductImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

interface ProductImageServiceInterface
{
    public function findByProductId(string $productId): ?Collection;
    public function find(string $id): ?Collection;
    public function store(array $data): ProductImage;
    public function attach(string $productId,UploadedFile $file,bool $isPrimary = false,int $position): ProductImage;
    public function replace(string $productImageId, UploadedFile $file): ProductImage;
    public function delete(string $imageId);
    public function update(string $id, array $data): ProductImage;
}
