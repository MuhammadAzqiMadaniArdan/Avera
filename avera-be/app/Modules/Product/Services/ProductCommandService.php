<?php

namespace App\Modules\Product\Services;

use App\Modules\Category\Repositories\CategoryRepository;
use App\Modules\Product\Contracts\ProductCommandServiceInterface;
use App\Modules\Product\Domain\ProductModeration;
use App\Modules\Product\Domain\ProductRules;
use App\Modules\Product\Domain\ProductStatus;
use App\Modules\Product\Models\Product;
use App\Modules\Product\Repositories\ProductRepository;
use App\Modules\Store\Repositories\StoreRepository;
use App\Traits\CacheVersionable;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class ProductCommandService implements ProductCommandServiceInterface
{
    use CacheVersionable;

    public function __construct(
        private ProductRepository $products,
        private ProductImageService $images,
        private ProductRules $rules,
        private ProductModeration $moderation,
        private CategoryRepository $categories,
        private StoreRepository $stores,
    ) {}


    public function store(array $data): Product
    {
        $data['slug'] = Str::slug($data['name']);
        $product = $this->products->store($data);
        $this->invalidateCache('products');
        return $product;
    }

    public function storeSeller(array $data): Product
    {
        $user = auth()->user();
        if (!$user) throw new Exception('User belum login');
        $store = $this->stores->getSellerStore($user->id, true);
        $data['store_id'] = $store->id;
        $data['slug'] = Str::slug($data['name']);
        $product = $this->products->store($data);
        $this->invalidateCache('products');
        return $product;
    }

    public function publish(string $id, array $data, array $imageIds = []): Product
    {
        $product = $this->products->findOrFail($id);
        Gate::authorize('update', $product->store);

        if ($product->status === ProductStatus::DRAFT->value) {
            $this->handleFirstPublish($product, $data, $imageIds);
        } else {
            $this->activate($product);
        }

        $this->invalidateCache('products');
        return $product->fresh();
    }

    private function handleFirstPublish(
        Product $product,
        array $data,
        array $imageIds
    ): void {
        $images = $this->getProductImage($product);

        $primary = $this->rules->resolvePrimaryImage($images);
        $this->rules->validateProductImage($images, $imageIds);

        $this->products->update($product, [
            ...$data,
            ...$this->moderation->resolve($product),
            'status' => ProductStatus::ACTIVE->value,
            'primary_image_id' => $primary->image_id,
            'published_at' => now(),
        ]);
    }
    private function activate(Product $product): void
    {
        if ($product->status === ProductStatus::ACTIVE->value) {
            return;
        }

        $this->products->update($product, [
            'status' => ProductStatus::ACTIVE->value,
        ]);
    }


    public function inactive(string $id): Product
    {
        $product = $this->products->findOrFail($id);
        Gate::authorize('update', $product->store);

        $this->products->update($product, [
            'status' => ProductStatus::INACTIVE->value,
        ]);

        $this->invalidateCache('products');
        return $product->fresh();
    }
    public function archive(string $id): Product
    {
        $product = $this->products->findOrFail($id);
        Gate::authorize('update', $product->store);

        $this->products->update($product, [
            'status' => ProductStatus::ARCHIVED->value,
        ]);

        $this->invalidateCache('products');
        return $product->fresh();
    }

    public function update(string $id, array $data, array $imageIds): Product
    {
        $product = $this->products->findOrFail($id);

        $images = $this->getProductImage($product);
        $this->rules->validateProductImage($images, $imageIds);

        $this->products->update($product, $data);
        $this->invalidateCache('products');

        return $product->fresh();
    }

    public function delete(string $id): bool
    {
        $this->invalidateCache('products');
        return $this->products->delete(
            $this->products->find($id)
        );
    }

    public function deletePermanent(string $id): bool
    {
        $this->invalidateCache('products');
        return $this->products->deletePermanent(
            $this->products->findByTrashed($id)
        );
    }

    public function restore(string $id): bool
    {
        $this->invalidateCache('products');
        return $this->products->restore(
            $this->products->findByTrashed($id)
        );
    }


    public function recalculateByImageId(string $imageId): void
    {
        foreach ($this->products->findByImageId($imageId) as $product) {
            $this->recalculate($product);
        }
    }

    private function recalculate(Product $product): void
    {
        if ($product->images()->where('images.moderation_status', 'pending')->exists()) {
            return;
        }

        $primary = $product->images()
            ->wherePivot('is_primary', true)
            ->first();

        if (!$primary) return;

        $this->products->update($product, [
            ...$this->moderation->resolve($product),
            'primary_image_id' => $primary->id,
            'moderated_at' => now(),
        ]);
    }

    private function getProductImage(Product $product): Collection
    {
        return $product->productImages()
            ->with('image')
            ->get();
    }
}
