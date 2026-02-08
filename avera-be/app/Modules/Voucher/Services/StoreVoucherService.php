<?php

namespace App\Modules\Voucher\Services;

use App\Modules\User\Repositories\UserRepository;
use App\Modules\Voucher\Contracts\StoreVoucherServiceInterface;
use App\Modules\Voucher\Models\StoreVoucher;
use App\Modules\Voucher\Repositories\StoreVoucherRepository;
use App\Traits\CacheVersionable;
use Illuminate\Pagination\LengthAwarePaginator;

class StoreVoucherService implements StoreVoucherServiceInterface
{
    use CacheVersionable;
    public function __construct(
        private StoreVoucherRepository $storeVoucherRepository,
        private UserRepository $userRepository
    ) {}
    public function get(int $perPage): LengthAwarePaginator
    {
        return $this->storeVoucherRepository->get($perPage);
    }
    public function find(string $id): ?StoreVoucher
    {
        return $this->storeVoucherRepository->find($id);
    }
    public function getStore(string $storeId): LengthAwarePaginator
    {
        return $this->storeVoucherRepository->getStore($storeId);
    }
    public function getAllStore(int $perPage): LengthAwarePaginator
    {
        return $this->storeVoucherRepository->getAllStore($perPage);
    }
    public function store(array $data): StoreVoucher
    {
        $store = auth()->user()->store;
        if (!$store) abort(403, 'User tidak memiliki akses');
        $data['store_id'] = $store->id;
        $storeVoucher = $this->storeVoucherRepository->store($data);
        $this->invalidateCache('storeVouchers');
        return $storeVoucher;
    }
    public function update(string $id, array $data): StoreVoucher
    {
        $storeVoucher = $this->storeVoucherRepository->find($id);
        $result = $this->storeVoucherRepository->update($storeVoucher, $data);
        $this->invalidateCache('storeVouchers');
        return $result;
    }
    public function delete(string $id): bool
    {
        $storeVoucher = $this->storeVoucherRepository->find($id);
        return $this->storeVoucherRepository->delete($storeVoucher);
    }
    public function deletePermanent(string $id): bool
    {
        $storeVoucher = $this->storeVoucherRepository->findByTrashed($id);
        return $this->storeVoucherRepository->deletePermanent($storeVoucher);
    }
    public function restore(string $id): bool
    {
        $storeVoucher = $this->storeVoucherRepository->findByTrashed($id);
        return $this->storeVoucherRepository->restore($storeVoucher);
    }
    public function getByTrashed(int $perPage): LengthAwarePaginator
    {
        return $this->storeVoucherRepository->getByTrashed();
    }
    public function findByTrashed(string $id): ?StoreVoucher
    {
        return $this->storeVoucherRepository->findByTrashed($id);
    }
}
