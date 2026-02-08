<?php

namespace App\Modules\Promotion\Services;

use App\Exceptions\ResourceNotFoundException;
use App\Modules\Promotion\Models\Promotion;
use App\Modules\Promotion\Repositories\PromotionRepository;
use App\Modules\User\Repositories\UserRepository;
use App\Traits\CacheVersionable;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;

class CampaignService implements CampaignServiceInterface
{
    use CacheVersionable;
    public function __construct(private PromotionRepository $promotionRepository, private UserRepository $userRepository) {}
    public function get(int $perPage): LengthAwarePaginator
    {
        return $this->promotionRepository->get($perPage);
    }
    public function find(string $id): ?Promotion
    {
        return $this->promotionRepository->find($id);
    }
    public function getStore(string $storeId): LengthAwarePaginator
    {
        return $this->promotionRepository->getStore($storeId);
    }
    public function getAllStore(int $perPage): LengthAwarePaginator
    {
        return $this->promotionRepository->getAllStore($perPage);
    }
    public function storeAdmin(array $data): Promotion
    {
        $data['owner_type'] = 'admin';
        $promotion = $this->promotionRepository->store($data);
        $this->invalidateCache('promotions');
        return $promotion;
    }
    public function storeUser(array $data, string $userId): Promotion
    {
        $user = $this->userRepository->findByIdentityCoreId($userId);
        $store = $user->store()->first();
        if (!$store) throw new ResourceNotFoundException('Store');
        $data['owner_type'] = 'store';
        $data['store_id'] = $store->id;
        $result = $this->promotionRepository->store($data);
        $this->invalidateCache('promotions');
        return $result;
    }
    public function update(string $id, array $data): Promotion
    {
        $promotion = $this->promotionRepository->find($id);
        $result = $this->promotionRepository->update($promotion, $data);
        $this->invalidateCache('promotions');
        return $result;
    }
    public function delete(string $id): bool
    {
        $promotion = $this->promotionRepository->find($id);
        if($promotion->owner_type === 'admin') throw new Exception('Anda tidak dapat menghapus promo admin !');
        return $this->promotionRepository->delete($promotion);
    }
    public function deleteAdmin(string $id): bool
    {
        $promotion = $this->promotionRepository->find($id);
        return $this->promotionRepository->delete($promotion);
    }
    public function deletePermanent(string $id): bool
    {
        $promotion = $this->promotionRepository->findByTrashed($id);
        return $this->promotionRepository->deletePermanent($promotion);
    }
    public function restore(string $id): bool
    {
        $promotion = $this->promotionRepository->findByTrashed($id);
        return $this->promotionRepository->restore($promotion);
    }
    public function findBySlug(string $slug): ?Promotion
    {
        return $this->promotionRepository->findBySlug($slug);
    }
    public function getByTrashed(int $perPage): LengthAwarePaginator
    {
        return $this->promotionRepository->getByTrashed();
    }
    public function findByTrashed(string $id): ?Promotion
    {
        return $this->promotionRepository->findByTrashed($id);
    }
}
