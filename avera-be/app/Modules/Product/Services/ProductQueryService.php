<?php

namespace App\Modules\Product\Services;

use App\Exceptions\ResourceNotFoundException;
use App\Helpers\UserContext;
use App\Modules\Category\Repositories\CategoryRepository;
use App\Modules\Product\Contracts\ProductQueryServiceInterface;
use App\Modules\Product\Models\Product;
use App\Modules\Product\Repositories\ProductRepository;
use App\Modules\Store\Repositories\StoreRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class ProductQueryService implements ProductQueryServiceInterface
{
    public function __construct(
        private ProductRepository $products,
        private CategoryRepository $categories,
        private StoreRepository $stores,
    ) {}


    public function filter(array $filters): LengthAwarePaginator
    {
        return $this->products->filter(
            $filters,
            UserContext::current()
        );
    }

    public function getProductByTrashed(array $filters): LengthAwarePaginator
    {
        $filters['trashed'] = true;
        return $this->filter($filters);
    }

    public function getTopProduct(): LengthAwarePaginator
    {
        $filters['top'] = true;
        return $this->filter($filters);
    }

    public function getProductByCategory(string $slug, array $filters): LengthAwarePaginator
    {
        $filters['category_id'] =
            $this->categories->findBySlug($slug, true)->id;

        return $this->filter($filters);
    }

    public function getProductByStoreSlug(string $slug, array $filters): LengthAwarePaginator
    {
        $filters['store_id'] =
            $this->stores->findBySlug($slug)->id;

        return $this->filter($filters);
    }

    public function getProductSellerByStoreCategory(string $slug, array $filters): LengthAwarePaginator
    {

        $store = $this->authorizeSellerStore();
        $filters['store_id'] = $store->id;
        $filters['category_id'] =
            $this->categories->findBySlug($slug, true)->id;

        return $this->filter($filters);
    }

    public function getProductSeller(array $filters): LengthAwarePaginator
    {
        $store = $this->authorizeSellerStore();
        $filters['store_id'] = $store->id;

        return $this->filter($filters);
    }

    public function getRandomProduct(): Collection
    {
        return $this->products->getRandomForHomepage();
    }

    public function getRandomDiscovery(): LengthAwarePaginator
    {
        return $this->products->getRandomDiscovery();
    }

    public function find(string $compound): ?Product
    {
        $ids = $this->parseCompoundId($compound);

        $product = $this->products->find($ids['product_id']);

        if (!$product || $product->store_id !== $ids['store_id']) {
            throw new ResourceNotFoundException('product');
        }

        $this->incrementClick($product);

        return $product;
    }

    public function findDetail(string $compound): ?Product
    {
        $ids = $this->parseCompoundId($compound);

        $product = $this->products->findOrFail($ids['product_id']);

        if (!$product || $product->store_id !== $ids['store_id']) {
            throw new ResourceNotFoundException('product');
        }

        $this->incrementClick($product);

        return $product;
    }

    public function findById(string $id): ?Product
    {
        return $this->products->find($id);
    }

    public function findByTrashed(string $id): ?Product
    {
        return $this->products->findByTrashed($id);
    }

    private function authorizeSellerStore()
    {
        $store = auth()->user()->store;
        Gate::authorize('viewSellerProduct', $store);
        return $this->stores->findOrFail($store->id);
    }

    private function parseCompoundId(string $compound): array
    {
        preg_match('/-i\.([a-f0-9\-]+)\.([a-f0-9\-]+)$/i', $compound, $matches);

        if (!isset($matches[1], $matches[2])) {
            throw new ResourceNotFoundException('product');
        }

        return [
            'store_id' => $matches[1],
            'product_id' => $matches[2],
        ];
    }

    private function incrementClick(Product $product): void
    {
        $ctx = UserContext::current();

        if ($ctx->isGuest() || $ctx->isAdmin() || $ctx->isSeller()) {
            return;
        }

        $product->increment('views_count');
    }
}
