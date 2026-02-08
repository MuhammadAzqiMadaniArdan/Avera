<?php

namespace App\Modules\User\Repositories;

use App\Exceptions\ResourceNotFoundException;
use App\Modules\User\Contracts\UserAddressRepositoryInterface;
use App\Modules\User\Models\UserAddress;
use App\Traits\CacheVersionable;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class UserAddressRepository implements UserAddressRepositoryInterface
{
    use CacheVersionable;
    public function __construct(private UserAddress $model) {}
    public function get(string $userId): Collection
    {
        return $this->model->where('user_id', $userId)
            ->orderBy('updated_at', 'DESC')->get();
    }
    public function find(string $id): ?UserAddress
    {
        $version = $this->versionKey('user_address');
        $category = Cache::remember("user_address:list:categoryId:{$id}:v{$version}", now()->addMinutes(1), fn() => $this->model->find($id));
        return $category;
    }
    public function findByUserId(string $userId)
    {
        return Cache::remember(
            "user_address:user:{$userId}",
            now()->addMinutes(1),
            fn() => $this->model->where('user_id', $userId)->get()
        );
    }
    public function store(array $data): UserAddress
    {
        return $this->model->create($data);
    }
    public function update(UserAddress $category, array $data): UserAddress
    {
        $category->update($data);
        return $category;
    }
    public function delete(UserAddress $category): bool
    {
        return $category->delete();
    }
    public function existsByUser(string $userId): bool
    {
        return $this->model->where('user_id', $userId)->exists();
    }
    public function countDataUser(string $userId): bool
    {
        return $this->model->where('user_id', $userId)->count();
    }
    public function unsetDefaultByUser(string $userId): void
    {
        $this->model->where('user_id', $userId)->where('is_default', true)->update(['is_default' => false]);
    }
}
