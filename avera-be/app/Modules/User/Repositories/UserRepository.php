<?php

namespace App\Modules\User\Repositories;

use App\Exceptions\ResourceNotFoundException;
use App\Helpers\AuthHelper;
use App\Modules\User\Contracts\UserRepositoryInterface;
use App\Modules\User\Models\User;
use App\Traits\CacheVersionable;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class UserRepository implements UserRepositoryInterface
{
    use CacheVersionable;
    public function __construct(private User $model) {}

    public function findByIdentityCoreId(string $id): ?User
    {
        $user = $this->model->where('identity_core_id', $id)->first();
        if (!$user) {
            throw new \Exception('user tidak ditemukan');
        }
        return $user;
    }
    public function getProfile(string $identityId): User
    {
        $version = $this->versionKey('users');
        $user = Cache::remember(
            "users:profile:by-identity:{$identityId}:v{$version}",
            now()->addMinutes(5),
            fn() => $this->model->where('identity_core_id', $identityId)->with('avatar')->first()
        );
        return $user;
    }
    public function storeProfile(string $identityId, string $email): User
    {
        $user = $this->model->firstOrCreate(
            ['identity_core_id' => $identityId],
            [
                'email' => $email,
                'name' => Str::random(6),
            ]
        );
        return $user;
    }

    public function update(User $user, array $data): User
    {
        $user->update($data);
        return $user;
    }

    // --- admin section --- 

    private function baseAdminQuery(array $filters)
    {
        $allowedSortBy = ['created_at', 'email', 'name', 'phone_number', 'violation_count', 'username'];
        $sortBy = in_array($filters['sort'] ?? null, $allowedSortBy) ? $filters['sort'] : 'created_at';
        $direction = in_array($filters['order_direction'], ['asc', 'desc']) ? $filters['order_direction'] : 'desc';

        return $this->model->query()
            ->when($filters['status'] ?? null, fn($q, $v) => $q->where('status', $v))
            ->when($filters['gender'] ?? null, fn($q, $v) => $q->where('gender', $v))
            ->orderBy($sortBy, $direction);
    }
    public function getAdmin(array $filters): LengthAwarePaginator
    {
        $allowedPerPage = [12, 24, 48];
        $perPage = in_array($filters['per_page'], $allowedPerPage) ? $filters['per_page'] : 24;
        return $this->baseAdminQuery($filters)->paginate($perPage);
    }

    public function getByTrashed(array $filters): LengthAwarePaginator
    {
        $allowedPerPage = [12, 24, 48];
        $perPage = in_array($filters['per_page'], $allowedPerPage) ? $filters['per_page'] : 24;
        return $this->baseAdminQuery($filters)->onlyTrashed()->paginate($perPage);
    }
    public function find(string $id): ?User
    {
        return $this->model->find($id);
    }
    public function findOrFail(string $id): ?User
    {
        $user = $this->model->find($id);
        if(!$user) throw new ResourceNotFoundException('User');
        return $user;
    }
    public function findWithAddress(string $id): ?User
    {
        $user = $this->model->find($id);
        if(!$user) throw new ResourceNotFoundException('User');
        $user->with('userAddressDefault');
        return $user;
    }
    public function findByTrashed(string $id): ?User
    {
        return $this->model->onlyTrashed()->find($id);
    }
    public function delete(User $user): bool
    {
        return $user->delete();
    }
    public function deletePermanent(User $user): bool
    {
        return $user->deletePermanent();
    }
    public function restore(User $user): bool
    {
        return $user->restore();
    }
}
