<?php

namespace App\Modules\Product\Services;

use App\Jobs\ModerateImageJob;
use App\Modules\Image\Services\ImageService;
use App\Modules\Product\Contracts\ProductImageServiceInterface;
use App\Modules\Product\Models\ProductImage;
use App\Modules\Product\Repositories\ProductImageRepository;
use App\Traits\CacheVersionable;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ProductImageService implements ProductImageServiceInterface
{
    use CacheVersionable;
    public function __construct(
        private ProductImageRepository $productImages,
        private ProductQueryService $productQuery,
        private ImageService $images
    ) {}

    public function findByProductId(string $productId): ?Collection
    {
        return $this->productImages->findByProductId($productId);
    }
    public function find(string $id): ?Collection
    {
        return $this->productImages->find($id);
    }
    public function store(array $data): ProductImage
    {
        return $this->attach($data['product_id'], $data['image'], $data['is_primary'] ?? false, $data['position'] ?? 0);
    }
    public function attach(
        string $productId,
        UploadedFile $file,
        bool $isPrimary = false,
        int $position
    ): ProductImage {
        $product = $this->productQuery->findById($productId);

        if ($isPrimary && $position !== 0) {
            throw new \Exception('Foto Primary Image harus di posisi pertama');
        }
        if ($isPrimary && $product->primary_image_id) {
            throw new \Exception('Foto Product Primary sudah ada silakan hapus atau replace dulu foto produk utama');
        }
        if ($product->images()->wherePivot('position', $position)->exists()) {
            throw new \Exception("Foto Produk dengan position $position sudah ada silakan replace atau hapus terlebih dahulu");
        }

        return DB::transaction(function () use ($file, $productId, $isPrimary, $position) {

            $image = $this->images->upload($file);

            $data = [
                'product_id' => $productId,
                'image_id' => $image->id,
                'is_primary' => $isPrimary,
                'position' => $position
            ];

            $productImage = $this->productImages->store($data);

            return $productImage;
        });
    }

    public function replace(string $productImageId, UploadedFile $file): ProductImage
    {
        return DB::transaction(function () use ($productImageId, $file) {

            $productImage = $this->productImages->find($productImageId);

            if ($productImage->replace_count >= 3) {
                throw new \Exception('Gambar ini terlalu sering diganti dan memerlukan review admin');
            }

            $imageId = $productImage->image->id;
            $this->images->delete($imageId);

            $newImage = $this->images->upload($file);

            $productImage->update([
                'image_id' => $newImage->id,
                'last_replaced_at' => now()
            ]);

            $productImage->increment('replace_count');

            ModerateImageJob::dispatch($newImage->id);

            return $productImage;
        });
    }

    public function delete(string $imageId)
    {
        return DB::transaction(function () use ($imageId) {
            $image = $this->productImages->find($imageId);
            $this->productImages->delete($image);
        });
    }
    public function update(string $id, array $data): ProductImage
    {
        $productImage = $this->productImages->find($id);
        $this->productImages->update($productImage, $data);
        $this->invalidateCache('productImages');
        return $productImage;
    }

    public function validateAndApply(
        Collection $images,
        array $imageIds,
        string $mode
    ): void {
        if ($images->count() !== count($imageIds)) {
            throw ValidationException::withMessages([
                'images' => 'Invalid images'
            ]);
        }

        foreach ($images as $image) {
            $status = $image->image->moderation_status;

            if ($status === 'rejected') {
                throw new \DomainException(
                    "Gambar ke-{$image->position} melanggar kebijakan"
                );
            }

            if ($status === 'pending') {
                throw new \DomainException(
                    'Image sedang dimoderasi'
                );
            }

            $this->applyMode($image, $mode);
        }
    }

    private function applyMode(ProductImage $image, string $mode): void
    {
        match ($mode) {
            'publish'  => $this->productImages->update($image, ['status' => 'active']),
            'inactive' => $this->productImages->update($image, ['status' => 'inactive']),
            default    => null,
        };
    }
}
