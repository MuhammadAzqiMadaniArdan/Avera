<?php

namespace App\Modules\Order\Repositories;

use App\Exceptions\ResourceNotFoundException;
use App\Modules\Order\Contracts\ReviewRepositoryInterface;
use App\Modules\Order\Models\Review;
use App\Traits\CacheVersionable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class ReviewRepository implements ReviewRepositoryInterface
{
    use CacheVersionable;

    public function __construct(
        private Review $model
    ) {}

    public function get($perPage = 10): LengthAwarePaginator
    {
        $version = $this->versionKey('reviews');
        return Cache::remember(
            "reviews:list:v{$version}",
            now()->addMinutes(5),
            fn() => $this->model->where('status', 'active')->orderBy('updated_at', 'DESC')->paginate($perPage)
        );
    }

    public function filter(array $filters): LengthAwarePaginator
    {
        $query = $this->model->query();

        if (!empty($filters['product_id'])) {
            $query->where('product_id', $filters['product_id']);
        }

        if (!empty($filters['keyword'])) {
            $this->applySearch($query, $filters['keyword']);
        }

        if (!empty($filters['rating']) && $filters['rating'] !== 0) {
            $query->where('rating', $filters['rating']);
        }

        return $query->with([
            'user.avatar',
            'product',
        ])->paginate($filters['per_page']);
    }

    private function applySearch(Builder $q, string $keyboard): void
    {
        $q->where(function ($qq) use ($keyboard) {
            $qq->where('user.name', 'ILIKE', "%{$keyboard}%")
                ->orWhere('product.name', 'ILIKE', "%{$keyboard}%");
        });
    }

    public function getByProduct(array $filters): LengthAwarePaginator
    {
        return $this->model->where('product_id',)
            ->orderBy('updated_at', 'DESC')
            ->paginate(6);
    }
    public function find(string $id): ?Review
    {
        $version = $this->versionKey('reviews');
        $review = Cache::remember("reviews:list:reviewId:{$id}:v{$version}", now()->addMinutes(1), fn() => $this->model->find($id));
        if (!$review) {
            throw new ResourceNotFoundException("Review");
        }
        return $review;
    }

    public function findBySlug(string $slug): ?Review
    {
        $version = $this->versionKey('reviews');
        $review = Cache::remember(
            "reviews:list:slug:{$slug}:v{$version}",
            now()->addMinutes(1),
            fn() => $this->model->where('slug', $slug)->first()
        );
        if (!$review) {
            throw new ResourceNotFoundException("Review");
        }
        return $review;
    }
    public function store(array $data): Review
    {
        return $this->model->create($data);
    }
    public function update(Review $review, array $data): Review
    {
        $review->update($data);
        return $review;
    }
    public function delete(Review $review): bool
    {
        return $review->delete();
    }
    public function deletePermanent(Review $review): bool
    {
        return $review->deletePermanent();
    }
    public function restore(Review $review): bool
    {
        return $review->restore();
    }
    public function getAdmin($perPage = 10): LengthAwarePaginator
    {
        $version = $this->versionKey('reviews');
        return Cache::remember(
            "reviews:list:admin:v{$version}",
            now()->addMinutes(5),
            fn() => $this->model->orderBy('updated_at', 'DESC')->paginate($perPage)
        );
    }
    public function getByTrashed($perPage = 10): LengthAwarePaginator
    {
        $version = $this->versionKey('reviews');
        return Cache::remember(
            "reviews:list:onlyTrashed:v{$version}",
            now()->addMinutes(5),
            fn() => $this->model->onlyTrashed()->orderBy('updated_at', 'DESC')->paginate($perPage)
        );
    }
    public function findByTrashed(string $id): ?Review
    {
        $version = $this->versionKey('reviews');
        $review = Cache::remember("reviews:list:reviewId:{$id}:byTrashed:v{$version}", now()->addMinutes(1), fn() => $this->model->onlyTrashed()->find($id));
        if (!$review) {
            throw new ResourceNotFoundException("Review");
        }
        return $review;
    }
}
