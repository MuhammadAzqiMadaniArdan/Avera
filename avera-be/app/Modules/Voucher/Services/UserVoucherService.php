<?php

namespace App\Modules\Voucher\Services;

use App\Exceptions\ResourceNotFoundException;
use App\Modules\User\Repositories\UserRepository;
use App\Modules\Voucher\Contracts\UserVoucherServiceInterface;
use App\Modules\Voucher\Models\UserVoucher;
use App\Modules\Voucher\Repositories\CampaignVoucherRepository;
use App\Modules\Voucher\Repositories\StoreVoucherRepository;
use App\Modules\Voucher\Repositories\UserVoucherRepository;
use App\Traits\CacheVersionable;
use Carbon\Carbon;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class UserVoucherService implements UserVoucherServiceInterface
{
    use CacheVersionable;
    public function __construct(
        private UserVoucherRepository $userVoucherRepository,
        private UserRepository $userRepository,
        private StoreVoucherRepository $storeVoucherRepository,
        private CampaignVoucherRepository $campaignVoucherRepository
    ) {}

    public function get(int $perPage): LengthAwarePaginator
    {
        return $this->userVoucherRepository->get($perPage);
    }
    public function find(string $id): ?UserVoucher
    {
        return $this->userVoucherRepository->find($id);
    }
    public function getByStore(string $userId, string $storeId): ?UserVoucher
    {
        return $this->userVoucherRepository->getByStore($userId, $storeId);
    }
    public function claim(string $voucherId, string $type): UserVoucher
    {
        return DB::transaction(function () use ($voucherId, $type) {
            $user = auth()->user();
            $voucher = match ($type) {
                'store' => $this->storeVoucherRepository->find($voucherId),
                'campaign' => $this->campaignVoucherRepository->find($voucherId),
            };

            if (!$voucher) {
                throw new ResourceNotFoundException('Voucher');
            }
            if (!$voucher->is_claimable) {
                throw new Exception('Voucher not claimable');
            }
            if ($voucher->total_quota && $voucher->claimed_count >= $voucher->total_quota) {
                throw new Exception('User Sudah Habis silakan refresh halaman page');
            }

            $now = Carbon::now();

            if ($voucher->start_at && $now->lt($voucher->start_at)) throw new Exception('Voucher belum aktif');
            if ($voucher->end_at && $now->gt($voucher->end_at)) throw new Exception('Voucher sudah expired');
            $alreadyClaim = $this->userVoucherRepository->UserVoucherAleradyExist($user->id, $voucherId);

            if ($alreadyClaim) {
                throw new Exception('User sudah claim voucher');
            }

            $data = [
                'user_id' => $user->id,
                'voucherable_id' => $voucherId,
                'voucherable_type' => get_class($voucher),
                'status' => 'unused',
                'claimed_at' => now()
            ];
            $storeVoucher = $this->userVoucherRepository->store($data);
            $voucher->increment('claimed_count');

            $this->invalidateCache('storeVouchers');
            return $storeVoucher;
        });
    }
    public function useVoucher(UserVoucher $userVoucher)
    {
        if ($userVoucher->isUsed() || $userVoucher->isExpired()) {
            throw new Exception('Voucher tidak bisa dipakai');
        }

        $userVoucher->update([
            'status' => 'used',
            'used_at' => now()
        ]);

        return $userVoucher;
    }

    public function expiredVoucher()
    {
        $this->userVoucherRepository->expiredChecked();
    }
    public function store(array $data): UserVoucher
    {
        $user = auth()->user();
        $storeVoucher = $this->storeVoucherRepository->find($data['voucher_id']);
        $campaignVoucher = $this->campaignVoucherRepository->find($data['voucher_id']);
        if ($storeVoucher) {
            $data['store_voucher_id'] = $storeVoucher->id;
        }
        if ($campaignVoucher) {
            $data['campaign_voucher_id'] = $campaignVoucher->id;
        }
        if (!$storeVoucher && !$campaignVoucher) {
            throw new Exception('Voucher id tidak valid');
        }

        $data['user_id'] = $user->id;
        $data['claimed_at'] = now();
        $data['used_at'] = null;
        $data['status'] = 'unused';
        $storeVoucher = $this->userVoucherRepository->store($data);
        $this->invalidateCache('storeVouchers');
        return $storeVoucher;
    }
    public function update(string $id, array $data): UserVoucher
    {
        $storeVoucher = $this->userVoucherRepository->find($id);
        $result = $this->userVoucherRepository->update($storeVoucher, $data);
        $this->invalidateCache('storeVouchers');
        return $result;
    }
    public function delete(string $id): bool
    {
        $storeVoucher = $this->userVoucherRepository->find($id);
        return $this->userVoucherRepository->delete($storeVoucher);
    }
    public function deletePermanent(string $id): bool
    {
        $storeVoucher = $this->userVoucherRepository->findByTrashed($id);
        return $this->userVoucherRepository->deletePermanent($storeVoucher);
    }
    public function restore(string $id): bool
    {
        $storeVoucher = $this->userVoucherRepository->findByTrashed($id);
        return $this->userVoucherRepository->restore($storeVoucher);
    }
    public function getByTrashed(int $perPage): LengthAwarePaginator
    {
        return $this->userVoucherRepository->getByTrashed();
    }
    public function findByTrashed(string $id): ?UserVoucher
    {
        return $this->userVoucherRepository->findByTrashed($id);
    }
}
