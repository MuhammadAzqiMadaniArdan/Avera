<?php

namespace App\Modules\Voucher\Repositories;

use App\Exceptions\ResourceNotFoundException;
use App\Modules\Voucher\Contracts\StoreVoucherRepositoryInterface;
use App\Modules\Voucher\Models\StoreVoucher;
use App\Traits\CacheVersionable;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class StoreVoucherRepository implements StoreVoucherRepositoryInterface
{
    use CacheVersionable;
    public function __construct(private StoreVoucher $model) {}
    public function get($perPage = 10): LengthAwarePaginator
    {
        $version = $this->versionKey('voucherStores');
        $page = request()->get('page', 1);
        return Cache::remember(
            "voucher:store:list:admin:page:{$page}v{$version}",
            now()->addMinutes(5),
            fn() => $this->model->orderBy('updated_at', 'DESC')->where('owner_type','admin')->paginate($perPage)
        );
    }
    public function getAllStore($perPage = 10): LengthAwarePaginator
    {
        $version = $this->versionKey('voucherStores');
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
        $version = $this->versionKey('voucherStores');
        return Cache::remember(
            "voucher:store:list:storeId:{$storeId}v{$version}",
            now()->addMinutes(5),
            fn() => $this->model->where('owner_type', 'store')->where('store_id', $storeId)->orderBy('updated_at', 'DESC')->paginate($perPage)
        );
    }
    public function find(string $id): ?StoreVoucher
    {
        $version = $this->versionKey('voucherStores');
        $storeVoucher = Cache::remember("voucher:store:list:storeVoucherId:{$id}:v{$version}", now()->addMinutes(1), fn() => $this->model->find($id));
        return $storeVoucher;
    }

    public function findBySlug(string $slug): ?StoreVoucher
    {
        $version = $this->versionKey('voucherStores');
        $storeVoucher = Cache::remember(
            "voucher:store:list:slug:{$slug}:v{$version}",
            now()->addMinutes(1),
            fn() => $this->model->where('slug', $slug)->with('vouchers')->first()
        );
        return $storeVoucher;
    }
    public function store(array $data): StoreVoucher
    {
        return $this->model->create($data);
    }
    public function update(StoreVoucher $storeVoucher, array $data): StoreVoucher
    {
        $storeVoucher->update($data);
        return $storeVoucher;
    }
    public function delete(StoreVoucher $storeVoucher): bool
    {
        return $storeVoucher->delete();
    }
    public function deletePermanent(StoreVoucher $storeVoucher): bool
    {
        return $storeVoucher->deletePermanent();
    }
    public function restore(StoreVoucher $storeVoucher): bool
    {
        return $storeVoucher->restore();
    }
    public function getByTrashed($perPage = 10): LengthAwarePaginator
    {
        $version = $this->versionKey('voucherStores');
        return Cache::remember(
            "voucher:store:list:onlyTrashed:v{$version}",
            now()->addMinutes(5),
            fn() => $this->model->onlyTrashed()->orderBy('updated_at', 'DESC')->paginate($perPage)
        );
    }
    public function findByTrashed(string $id): ?StoreVoucher
    {
        $version = $this->versionKey('voucherStores');
        $storeVoucher = Cache::remember("voucher:store:list:storeVoucherId:{$id}:byTrashed:v{$version}", now()->addMinutes(1), fn() => $this->model->onlyTrashed()->find($id));
        if (!$storeVoucher) {
            throw new ResourceNotFoundException("StoreVoucher");
        }
        return $storeVoucher;
    }
}
