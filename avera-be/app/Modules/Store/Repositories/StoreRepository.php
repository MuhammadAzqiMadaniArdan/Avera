<?php

namespace App\Modules\Store\Repositories;

use App\Exceptions\ResourceNotFoundException;
use App\Helpers\UserContext;
use App\Modules\Store\Contracts\StoreRepositoryInterface;
use App\Modules\Store\Models\Store;
use App\Modules\User\Models\User;
use App\Traits\CacheVersionable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class StoreRepository implements StoreRepositoryInterface
{
    use CacheVersionable;
    public function __construct(private Store $model) {}
    
    public function get(int $perPage): LengthAwarePaginator
    {
        throw new \Exception('Not implemented');
    }
    public function filter(array $filters, UserContext $ctx): LengthAwarePaginator
    {
        $query = $this->baseQuery($ctx);

        if (
            $ctx->isAdmin() &&
            !empty($filters['trashed'])
        ) {
            $query->onlyTrashed();
        }
        
        if (!empty($filters['keyword'])) {
            $this->applySearch($query, $filters['keyword']);
        }

        if ($ctx->isAdmin() &&
            !empty($filters['condition'])
        ) {
            $this->applyCondition(
                $query,
                $filters['condition'] ?? null,
                $filters['selected_condition'] ?? null,
            );
        }

        $allowed = $this->allowedSorts();

        $sort = in_array($filters['sort'] ?? null, $allowed)
            ? $filters['sort']
            : 'popular';

        $this->applySort(
            $query,
            $sort,
            $filters['order'] ?? 'desc'
        );

        return $query->paginate(48);
    }
    private function allowedSorts(): array
    {
        return ['name','rating','verified_at','created_at'];
    }
    private function baseQuery(UserContext $ctx): Builder
    {
        return $this->model->query()
            ->where('status', 'active')
            ->with('logo');
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
            'name'     => $q->orderBy('name', $direction),
            'rating'     => $q->orderBy('stock', $direction),
            'verified_at'     => $q->orderBy('verified_at', $direction),
            'created_at'     => $q->orderBy('created_at', $direction),
            default     => $q->orderBy('rating', 'desc'),
        };
    }
    private function applyCondition(Builder $q, string $condition, string $selected): void
    {
        match ($condition) {
            'verification_status'    => $q->where('verification_status', $selected),
            'status' => $q->where('status', $selected),
            'verified_by' => $q->where('verified_by', $selected),
            default => null
        };
    }

    public function find(string $id): ?Store
    {
        $version = $this->versionKey('stores');
        $store = Cache::remember("stores:storeId:{$id}:v{$version}", now()->addMinutes(1), fn() => $this->model->find($id));
        return $store;
    }

    public function findByUser(string $userId): ?Store
    {
        return $this->model->where('user_id',$userId)->first();
    }

    public function findOrFail(string $id): ?Store
    {
        $version = $this->versionKey('stores');
        $store = Cache::remember("stores:storeId:{$id}:v{$version}", now()->addMinutes(1), fn() => $this->model->find($id));
        if (!$store) {
            throw new ResourceNotFoundException("Store");
        }
        return $store;
    }

    public function getSellerStore(string $userId, bool $isFailMessage = false): ?Store
    {
        $store = $this->model->where('user_id', $userId)->first();
        if (!$store && $isFailMessage) {
            throw new ResourceNotFoundException('Store');
        }
        return $store;
    }

    public function findBySlug(string $slug, bool $isFailMessage = false): ?Store
    {
        $version = $this->versionKey('stores');
        $store = Cache::remember(
            "stores:slug:{$slug}:v{$version}",
            now()->addMinutes(1),
            fn() => $this->model->where('slug', $slug)->first()
        );
        if (!$store && $isFailMessage) {
            throw new ResourceNotFoundException('Store');
        }
        return $store;
    }

    public function store(array $data): Store
    {
        return $this->model->create($data);
    }

    public function update(Store $store, array $data): Store
    {
        $store->update($data);
        return $store;
    }

    public function delete(Store $store): bool
    {
        return $store->delete();
    }

    public function deletePermanent(Store $store): bool
    {
        return $store->deletePermanent();
    }

    public function restore(Store $store): bool
    {
        return $store->restore();
    }

    public function getAdmin($filters): LengthAwarePaginator
    {
        $allowedPerPage = [12, 24, 48];

        return $this->model->orderBy('updated_at', 'DESC')->paginate(10);
    }
    public function getStoreProfile(): LengthAwarePaginator
    {
        $version = $this->versionKey('stores');
        return Cache::remember(
            "stores:list:admin:v{$version}",
            now()->addMinutes(5),
            fn() => $this->model->orderBy('updated_at', 'DESC')->paginate(10)
        );
    }
    public function getByTrashed($perPage = 10): LengthAwarePaginator
    {
        $version = $this->versionKey('stores');
        return Cache::remember(
            "stores:list:onlyTrashed:v{$version}",
            now()->addMinutes(5),
            fn() => $this->model->onlyTrashed()->orderBy('updated_at', 'DESC')->paginate($perPage)
        );
    }
    public function findByTrashed(string $id): ?Store
    {
        $version = $this->versionKey('stores');
        $store = Cache::remember("stores:list:storeId:{$id}:byTrashed:v{$version}", now()->addMinutes(1), fn() => $this->model->onlyTrashed()->find($id));
        if (!$store) {
            throw new ResourceNotFoundException("Store");
        }
        return $store;
    }
}
