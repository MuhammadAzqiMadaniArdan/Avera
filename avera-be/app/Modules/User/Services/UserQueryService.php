<?php

namespace App\Modules\User\Services;

use App\Modules\User\Contracts\UserQueryServiceInterface;
use App\Modules\User\Models\User;
use App\Modules\User\Repositories\UserRepository;
use App\Traits\CacheVersionable;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Gate;

class UserQueryService implements UserQueryServiceInterface
{
    use CacheVersionable;
    public function __construct(
        private UserRepository $users
    ) {}

    public function find(string $id): User
    {
        return $this->users->find($id);
    }

    public function getProfile(string $identityId): User
    {
        $user = $this->users->getProfile($identityId);
        return $user;
    }

    public function getAdmin(array $filters): LengthAwarePaginator
    {
        Gate::authorize('viewAny');
        return $this->users->getAdmin($filters);
    }
    
    public function getByTrashed(array $filters): LengthAwarePaginator
    {
        return $this->users->getByTrashed($filters);
    }

    public function findByTrashed(string $id): ?User
    {
        return $this->users->findByTrashed($id);
    }
}
