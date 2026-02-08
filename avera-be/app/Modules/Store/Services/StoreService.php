<?php

namespace App\Modules\Store\Services;

use App\Exceptions\ResourceNotFoundException;
use App\Exceptions\UserAlreadyHasStoreException;
use App\Exceptions\VerificationStoreException;
use App\Helpers\UserContext;
use App\Modules\Banner\Models\Banner;
use App\Modules\Banner\Services\BannerService;
use App\Modules\Store\Contracts\StoreServiceInterface;
use App\Modules\Store\Models\Store;
use App\Modules\Store\Repositories\StoreRepository;
use App\Modules\User\Repositories\UserRepository;
use App\Traits\CacheVersionable;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StoreService implements StoreServiceInterface
{
    use CacheVersionable;
    public function __construct(
        private StoreRepository $stores,
        private UserRepository $users,
        private BannerService $banners
    ) {}
    public function get(array $filters): LengthAwarePaginator
    {
        return $this->stores->filter(
            $filters,
            UserContext::current()
        );
    }
    public function getAdmin(array $filters): LengthAwarePaginator
    {
        return $this->stores->filter(
            $filters,
            UserContext::current()
        );
    }
    public function getSellerStore(): ?Store
    {
        $user = auth()->user();
        return $this->stores->getSellerStore($user->id, true);
    }

    public function findBySlug(string $slug): ?Store
    {
        $store = $this->stores->findBySlug($slug, true);
        return $store;
    }
    public function findByUser(string $userId): ?Store
    {
        return $this->stores->findByUser($userId);
    }
    public function find(string $id): ?Store
    {
        return $this->stores->find($id);
    }
    public function storeAdmin(array $data): Store
    {
        if (!$data['slug']) {
            $generateSlug = Str::slug($data['name']);
            $slug = $this->generateSlug($generateSlug, $generateSlug, 1);
            $data['slug'] = $slug;
        }
        $store = $this->stores->store($data);
        $this->invalidateCache('stores');
        return $store;
    }
    public function storeUser(array $data): Store
    {
        $user = auth()->user();
        if (!$user) throw new Exception('User is not logged in');
        $storeUser = $this->stores->getSellerStore($user->id);
        if ($storeUser) {
            throw new UserAlreadyHasStoreException();
        }
        $generateSlug = Str::slug($data['name']);
        $slug = $this->generateSlug($generateSlug, $generateSlug, 1);
        return DB::transaction(function () use ($data,$slug,$user) {
            $data['slug'] = $slug;
            $data['user_id'] = $user->id;
            $userData = $this->users->findOrFail($user->id);
            $this->users->update($userData,['role' => 'seller']);
            $result = $this->stores->store($data);
            $this->invalidateCache('stores');
            return $result;
        });
    }
    public function storeBanner(array $data): Banner
    {
        $user = auth()->user();
        if (!$user) throw new Exception('User is not logged in');
        $store = $this->stores->getSellerStore($user->id);
        if (empty($store)) throw new ResourceNotFoundException('store');
        $data['owner_type'] = 'store';
        $data['type'] = 'store';
        $data['owner_id'] = $store->id;
        $data['created_by'] = $user->id;
        $result = $this->banners->store($data);
        $this->invalidateCache('stores');
        return $result;
    }
    public function verification(string $id): Store
    {
        $store = $this->stores->find($id);
        if ($store->verification_status === 'pending') {
            throw new VerificationStoreException('');
        }
        if ($store->verification_status === 'verifed') {
            throw new VerificationStoreException('verified');
        }
        if ($store->verification_status === 'suspended') {
            throw new VerificationStoreException('suspended');
        }
        if (!$store->isActive()) {
            throw new VerificationStoreException('non-active');
        }
        if ($store->products()->where('status', 'active')->exists()) {
            throw new VerificationStoreException('product');
        }
        if (!$store->description) {
            throw new VerificationStoreException('description');
        }
        if (!$store->logo()->exists()) {
            throw new VerificationStoreException('logo');
        }
        $data = [
            'verification_status' => 'pending'
        ];
        $result = $this->stores->update($store, $data);
        $this->invalidateCache('stores');
        return $result;
    }
    public function verified(string $id): Store
    {
        $data = ['verification_status' => 'verified'];
        return $this->update($id, $data);
    }
    public function rejected(string $id): Store
    {
        $data = ['verification_status' => 'rejected'];
        return $this->update($id, $data);
    }
    public function suspended(string $id): Store
    {
        $data = ['verification_status' => 'suspended'];
        return $this->update($id, $data);
    }
    public function update(string $id, array $data): Store
    {
        $store = $this->stores->find($id);
        $result = $this->stores->update($store, $data);
        $this->invalidateCache('stores');
        return $result;
    }
    public function delete(string $id): bool
    {
        $store = $this->stores->find($id);
        return $this->stores->delete($store);
    }
    public function deletePermanent(string $id): bool
    {
        $store = $this->stores->findByTrashed($id);
        return $this->stores->deletePermanent($store);
    }
    public function restore(string $id): bool
    {
        $store = $this->stores->findByTrashed($id);
        return $this->stores->restore($store);
    }
    public function getByTrashed(int $perPage): LengthAwarePaginator
    {
        return $this->stores->getByTrashed();
    }
    public function findByTrashed(string $id): ?Store
    {
        return $this->stores->findByTrashed($id);
    }
    private function generateSlug(string $slug, string $originName, int $number)
    {
        while ($this->stores->findBySlug($slug,false) != null) {
            $slug = "$originName-$number";
            $number++;
        }
        return $slug;
    }
}
