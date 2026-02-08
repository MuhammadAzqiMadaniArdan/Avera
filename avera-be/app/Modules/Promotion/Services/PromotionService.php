<?php

namespace App\Modules\Promotion\Services;

use App\Exceptions\ResourceNotFoundException;
use App\Modules\Promotion\Contracts\PromotionServiceInterface;
use App\Modules\Promotion\Models\Promotion;
use App\Modules\Promotion\Repositories\PromotionRepository;
use App\Modules\User\Repositories\UserRepository;
use App\Traits\CacheVersionable;
use Carbon\Carbon;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class PromotionService implements PromotionServiceInterface
{
    use CacheVersionable;
    public function __construct(private PromotionRepository $promotionRepository, private UserRepository $userRepository, private PromotionProductService $promotionProductService) {}
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
    public function store(array $data, string $userId): Promotion
    {
        return DB::transaction(function () use ($data, $userId) {
            $user = $this->userRepository->findByIdentityCoreId($userId);
            $store = $user->store()->first();
            if (!$store) throw new ResourceNotFoundException('Store');
            $data['store_id'] = $store->id;
            $data['type'] = 'product_discount';
            $result = $this->promotionRepository->store($data);
            $this->promotionProductService->store($data['products'], $result->id);
            $this->invalidateCache('promotions');
            return $result;
        });
    }
    public function update(string $id, array $data): Promotion
    {
        $promotion = $this->promotionRepository->find($id);
        $result = $this->promotionRepository->update($promotion, $data);
        $this->invalidateCache('promotions');
        return $result;
    }
    public function validatePromo(string $id, array $data): Promotion
    {
        $promotion = $this->promotionRepository->find($id);
        $now = Carbon::now();
        if ($now->lt($promotion->start_at)) throw new Exception('Promo Belum Ada');
        if ($now->gt($promotion->end_at)) throw new Exception('Promo sudah expired');
        return $promotion;
    }
    public function delete(string $id): bool
    {
        $promotion = $this->promotionRepository->find($id);
        if ($promotion->owner_type === 'admin') throw new Exception('Anda tidak dapat menghapus promo admin !');
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
    public function getByTrashed(int $perPage): LengthAwarePaginator
    {
        return $this->promotionRepository->getByTrashed();
    }
    public function findByTrashed(string $id): ?Promotion
    {
        return $this->promotionRepository->findByTrashed($id);
    }
}
