<?php

namespace App\Modules\Product\Repositories;

use App\Exceptions\ResourceNotFoundException;
use App\Helpers\UserContext;
use App\Modules\Product\Contracts\ProductRepositoryInterface;
use App\Modules\Product\Models\Product;
use App\Traits\CacheVersionable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class ProductRepository implements ProductRepositoryInterface
{

    use CacheVersionable;

    public function __construct(private Product $model) {}
    public function get(array $filters): LengthAwarePaginator
    {
        throw new \Exception('Not implemented');
    }
    public function getByTrashed(int $perPage): LengthAwarePaginator
    {
        throw new \Exception('Not implemented');
    }
    public function getRandomForHomepage(): Collection
    {
        $version = $this->versionKey('products');
        return Cache::remember(
            "products:homepage:list:v{$version}",
            now()->addMinutes(5),
            fn() =>
            $this->model->query()
                ->where('status', 'active')
                ->where('stock', '>', 0)
                ->where('moderation_visibility', 'public')
                ->with('primaryImage')
                ->limit(48)
                ->inRandomOrder()
                ->get()
        );
    }
    public function getRandomDiscovery(): LengthAwarePaginator
    {
        $version = $this->versionKey('products');
        $perPage = 48;
        return Cache::remember(
            "products:list:random:v{$version}",
            now()->addMinutes(5),
            fn() =>
            $this->model->query()
                ->where('status', 'active')
                ->where('stock', '>', 0)
                ->where('moderation_visibility', 'public')
                ->with('primaryImage')
                ->inRandomOrder()
                ->paginate($perPage)
        );
    }

    public function filter(array $filters, UserContext $ctx): LengthAwarePaginator
    {
        $query = $this->baseQuery($ctx);
        $query->with([
            'store',
            'category',
            'images',
            'primaryImage'
        ]);
        if (
            $ctx->isAdmin() &&
            !empty($filters['trashed'])
        ) {
            $query->onlyTrashed();
        }

        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (!empty($filters['store_id'])) {
            $query->where('store_id', $filters['store_id']);
        }

        if (!empty($filters['top'])) {
            $query->orderByDesc('sales_count');
        }


        if (!empty($filters['keyword'])) {
            $this->applySearch($query, $filters['keyword']);
        }

        if (($ctx->isAdmin() || $ctx->isSeller()) &&
            !empty($filters['condition'])
        ) {
            $this->applyCondition(
                $query,
                $filters['condition'] ?? null,
                $filters['selected_condition'] ?? null,
            );
        }

        $allowed = $this->allowedSorts($ctx);

        $sort = in_array($filters['sort'] ?? null, $allowed)
            ? $filters['sort']
            : 'popular';

        $this->applySort(
            $query,
            $sort,
            $filters['order'] ?? 'desc'
        );

        return $this->paginateWithCache($query, $filters, $ctx);
    }
    
    private function allowedSorts(UserContext $ctx): array
    {
        if ($ctx->isAdmin() || $ctx->isSeller()) {
            return [
                'popular',
                'latest',
                'top-sales',
                'name',
                'stock',
                'price',
                'views',
                'sales',
                'published',
            ];
        }

        return [
            'popular',
            'latest',
            'top-sales',
            'price',
        ];
    }

    private function baseQuery(UserContext $ctx): Builder
    {
        return $this->model->query()
            ->where('status', 'active')
            ->where('stock', '>', 0)
            ->whereIn('moderation_visibility', $this->resolveVisibility($ctx))
            ->with('primaryImage');
    }

    private function resolveVisibility(UserContext $ctx): array
    {
        if ($ctx->isAdmin()) {
            return ['public', 'limited', 'adult'];
        }

        return ['public', 'limited'];
    }
    private function applySearch(Builder $q, string $keyboard): void
    {
        $q->where(function ($qq) use ($keyboard) {
            $qq->where('name', 'ILIKE', "%{$keyboard}%")
                ->orWhere('description', 'ILIKE', "%{$keyboard}%");
        });
    }
    private function applySort(Builder $q, string $sort, string $direction): void
    {
        match ($sort) {
            'latest'    => $q->orderBy('published_at', 'desc'),
            'top-sales' => $q->orderBy('sales_count', 'desc'),
            'sales' => $q->orderBy('sales_count', $direction),
            'price'     => $q->orderBy('price', $direction),
            'name'     => $q->orderBy('name', $direction),
            'stock'     => $q->orderBy('stock', $direction),
            'published'     => $q->orderBy('published_at', $direction),
            'views'     => $q->orderBy('views_count', $direction),
            default     => $q->orderBy('views_count', 'desc'),
        };
    }
    private function applyCondition(Builder $q, string $condition, string $selected): void
    {
        match ($condition) {
            'age'    => $q->where('age', $selected),
            'status' => $q->where('status', $selected),
            'moderation_visibility' => $q->where('moderation_visibility', $selected),
            default => null
        };
    }
    private function paginateWithCache(
        Builder $q,
        array $filters,
        UserContext $ctx
    ): LengthAwarePaginator {

        $page = max((int) request()->query('page', 1), 1);

        if ($page > 3 || $ctx->isGuest()) {
            return $q->paginate(48);
        }

        $key = implode(':', [
            'products',
            'cat:' . ($filters['category_id'] ?? 'all'),
            'store:' . ($filters['store_id'] ?? 'all'),
            'q:' . md5($filters['keyword'] ?? ''),
            'sort:' . ($filters['sort'] ?? 'popular'),
            'vis:' . ($ctx->isAdmin() ? 'adult' : 'safe'),
            "page:{$page}",
        ]);

        return Cache::remember(
            $key,
            now()->addMinutes(5),
            fn() => $q->paginate(48)
        );
    }

    public function find(string $id): ?Product
    {
        $version = $this->versionKey('products');
        $product = Cache::remember("product:list:productId:{$id}:v{$version}", now()->addMinutes(1), fn() => $this->model->find($id));
        return $product;
    }
    public function findDetail(string $id): ?Product
    {
        $version = $this->versionKey('products');
        $product = Cache::remember(
            "product:list:productId:{$id}:v{$version}",
            now()->addMinutes(1),
            fn() => $this->model->query()
                ->find($id)
                ->with([
                    'store',
                    'category',
                    'images'
                ])->withCount('reviews')
                ->withAvg('reviews', 'rating')
        );
        return $product;
    }
    public function findOrFail(string $id): ?Product
    {
        $version = $this->versionKey('products');
        $product = Cache::remember(
            "product:list:productId:{$id}:v{$version}",
            now()->addMinutes(1),
            fn() => $this->model->query()
                ->with([
                    'store' => function ($q) {
                        $q->with('logo')
                            ->withCount([
                                'products as products_count' => fn($q) =>
                                $q->where('status', 'active')
                            ]);
                    },
                    'category',
                    'images',
                ])->withCount('reviews')
                ->withAvg('reviews', 'rating')
                ->find($id)
        );
        if (!$product) {
            throw new ResourceNotFoundException('product');
        }
        return $product;
    }
    public function findByImageId(string $imageId): Collection
    {
        return $this->model->whereHas(
            'images',
            function ($q) use ($imageId) {
                $q->where('images.id', $imageId);
            }
        )->get();
    }
    public function findByTrashed(string $id): ?Product
    {
        $version = $this->versionKey('products');
        $product = Cache::remember("product:list:onlyTrashed:productId:{$id}:v{$version}", now()->addMinutes(1), fn() => $this->model->onlyTrashed()->find($id));
        if (!$product) {
            throw new ResourceNotFoundException('product');
        }
        return $product;
    }
    public function store(array $data): Product
    {
        return $this->model->create($data);
    }
    public function update(Product $product, array $data): Product
    {
        $product->update($data);
        return $product;
    }
    public function delete(Product $product): bool
    {
        return $product->delete();
    }
    public function deletePermanent(Product $product): bool
    {
        return $product->deletePermanent();
    }
    public function restore(Product $product): bool
    {
        return $product->restore();
    }
    public function deductStock(Product $product,int $quantity): bool
    {
        return $product->update(['stock' => $product->stock - $quantity]);
    }
}
