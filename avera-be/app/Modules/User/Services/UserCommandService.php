<?php

namespace App\Modules\User\Services;

use App\Modules\Image\Services\ImageService;
use App\Modules\User\Contracts\UserCommandServiceInterface;
use App\Modules\User\Models\User;
use App\Modules\User\Repositories\UserRepository;
use App\Traits\CacheVersionable;
use Illuminate\Support\Facades\Gate;

class UserCommandService implements UserCommandServiceInterface
{
    use CacheVersionable;
    public function __construct(
        private UserRepository $users,
        private ImageService $imageService
    ) {}

    public function storeProfile(string $identityId, string $email): User
    {
        $result = $this->users->storeProfile($identityId, $email);
        $this->invalidateCache('users');
        return $result;
    }

    public function updateProfile(string $identityId, array $data): User
    {
        $user = $this->users->getProfile($identityId);
        Gate::authorize('updateProfile', $user);
        $result = $this->users->update($user, $data);
        $this->invalidateCache('users');
        return $result;
    }

    public function uploadAvatar(string $identityId, array $data): User
    {
        $user = $this->users->getProfile($identityId);
        Gate::authorize('updateProfile', $user);
        if (!$user->avatar) {
            $image = $this->imageService->upload($data['avatar'],'user_avatar',$user->id);
        } else {
            $image = $this->imageService->replace($user->avatar,$data['avatar']);
        }
        $result = $this->users->update($user, ['avatar_image_id' => $image->id]);
        $this->invalidateCache('users');
        return $result;
    }

    public function update(string $id, array $data): User
    {
        $user = $this->users->find($id);
        Gate::authorize('update', $user);
        $result = $this->users->update($user, $data);
        $this->invalidateCache('users');
        return $result;
    }

    public function delete(string $id): bool
    {
        $user = $this->users->find($id);
        return $this->users->delete($user);
    }

    public function deletePermanent(string $id): bool
    {
        $user = $this->users->findByTrashed($id);
        return $this->users->deletePermanent($user);
    }

    public function restore(string $id): bool
    {
        $user = $this->users->findByTrashed($id);
        return $this->users->restore($user);
    }
}
