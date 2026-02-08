<?php

namespace App\Modules\Voucher\Repositories;

use App\Exceptions\ResourceNotFoundException;
use App\Modules\Voucher\Contracts\UserVoucherRepositoryInterface;
use App\Modules\Voucher\Models\CampaignVoucher;
use App\Modules\Voucher\Models\StoreVoucher;
use App\Modules\Voucher\Models\UserVoucher;
use App\Traits\CacheVersionable;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class UserVoucherRepository implements UserVoucherRepositoryInterface
{
    use CacheVersionable;
    public function __construct(private UserVoucher $model) {}
    public function get($perPage = 10): LengthAwarePaginator
    {
        $version = $this->versionKey('user_vouchers');
        $page = request()->get('page', 1);
        return Cache::remember(
            "voucher:store:list:admin:page:{$page}v{$version}",
            now()->addMinutes(5),
            fn() => $this->model->orderBy('updated_at', 'DESC')->where('owner_type', 'admin')->paginate($perPage)
        );
    }
    public function getAllStore($perPage = 10): LengthAwarePaginator
    {
        $version = $this->versionKey('user_vouchers');
        $page = request()->get('page', 1);
        return Cache::remember(
            "voucher:store:list:store:page:{$page}:v{$version}",
            now()->addMinutes(5),
            fn() => $this->model->where('owner_type', 'store')->orderBy('updated_at', 'DESC')->paginate($perPage)
        );
    }
    public function getStore(string $storeId): LengthAwarePaginator
    {
        $perPage = 10;
        $version = $this->versionKey('user_vouchers');
        return Cache::remember(
            "voucher:store:list:storeId:{$storeId}v{$version}",
            now()->addMinutes(5),
            fn() => $this->model->where('owner_type', 'store')->where('store_id', $storeId)->orderBy('updated_at', 'DESC')->paginate($perPage)
        );
    }
    public function find(string $id): ?UserVoucher
    {
        $version = $this->versionKey('user_vouchers');
        $userVoucher = Cache::remember("voucher:store:list:userVoucherId:{$id}:v{$version}", now()->addMinutes(1), fn() => $this->model->find($id));
        return $userVoucher;
    }
    public function getByStore(string $userId,string $storeId): ?Collection
    {
        $userVoucher = $this->model->where('user_id',$userId)->where('voucherable_id',$storeId)->get();
        return $userVoucher;
    }
    public function expiredChecked(): ?UserVoucher
    {
        $userVoucher = $this->model->where('status', 'claimable')->whereHasMorph('voucherable', [StoreVoucher::class, CampaignVoucher::class], function ($q) {
            $q->where('end_at', '<', now());
        })->update(['status' => 'expired']);
        return $userVoucher;
    }

    public function findBySlug(string $slug): ?UserVoucher
    {
        $version = $this->versionKey('user_vouchers');
        $userVoucher = Cache::remember(
            "voucher:store:list:slug:{$slug}:v{$version}",
            now()->addMinutes(1),
            fn() => $this->model->where('slug', $slug)->with('vouchers')->first()
        );
        return $userVoucher;
    }
    public function UserVoucherAleradyExist(string $userId, string $voucherId): ?UserVoucher
    {
        $userVoucher = $this->model->where('user_id', $userId)->where('voucherable_id', $voucherId)->exist();
        return $userVoucher;
    }
    public function store(array $data): UserVoucher
    {
        return $this->model->create($data);
    }
    public function update(UserVoucher $userVoucher, array $data): UserVoucher
    {
        $userVoucher->update($data);
        return $userVoucher;
    }
    public function delete(UserVoucher $userVoucher): bool
    {
        return $userVoucher->delete();
    }
    public function deletePermanent(UserVoucher $userVoucher): bool
    {
        return $userVoucher->deletePermanent();
    }
    public function restore(UserVoucher $userVoucher): bool
    {
        return $userVoucher->restore();
    }
    public function getByTrashed($perPage = 10): LengthAwarePaginator
    {
        $version = $this->versionKey('user_vouchers');
        return Cache::remember(
            "voucher:store:list:onlyTrashed:v{$version}",
            now()->addMinutes(5),
            fn() => $this->model->onlyTrashed()->orderBy('updated_at', 'DESC')->paginate($perPage)
        );
    }
    public function findByTrashed(string $id): ?UserVoucher
    {
        $version = $this->versionKey('user_vouchers');
        $userVoucher = Cache::remember("voucher:store:list:userVoucherId:{$id}:byTrashed:v{$version}", now()->addMinutes(1), fn() => $this->model->onlyTrashed()->find($id));
        return $userVoucher;
    }
}
