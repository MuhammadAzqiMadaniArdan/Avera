<?php

namespace App\Modules\Voucher\Repositories;

use App\Exceptions\ResourceNotFoundException;
use App\Modules\Voucher\Contracts\CampaignVoucherRepositoryInterface;
use App\Modules\Voucher\Models\CampaignVoucher;
use App\Traits\CacheVersionable;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class CampaignVoucherRepository implements CampaignVoucherRepositoryInterface
{
    use CacheVersionable;
    public function __construct(private CampaignVoucher $model) {}
    public function get($perPage = 10): LengthAwarePaginator
    {
        $version = $this->versionKey('campaign_vouchers');
        $page = request()->get('page', 1);
        return Cache::remember(
            "voucher:store:list:admin:page:{$page}v{$version}",
            now()->addMinutes(5),
            fn() => $this->model->orderBy('updated_at', 'DESC')->where('owner_type','admin')->paginate($perPage)
        );
    }
    public function getAllStore($perPage = 10): LengthAwarePaginator
    {
        $version = $this->versionKey('campaign_vouchers');
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
        $version = $this->versionKey('campaign_vouchers');
        return Cache::remember(
            "voucher:store:list:storeId:{$storeId}v{$version}",
            now()->addMinutes(5),
            fn() => $this->model->where('owner_type', 'store')->where('store_id', $storeId)->orderBy('updated_at', 'DESC')->paginate($perPage)
        );
    }
    public function find(string $id): ?CampaignVoucher
    {
        $version = $this->versionKey('campaign_vouchers');
        $campaignVoucher = Cache::remember("voucher:store:list:campaignVoucherId:{$id}:v{$version}", now()->addMinutes(1), fn() => $this->model->find($id));
        return $campaignVoucher;
    }

    public function findBySlug(string $slug): ?CampaignVoucher
    {
        $version = $this->versionKey('campaign_vouchers');
        $campaignVoucher = Cache::remember(
            "voucher:store:list:slug:{$slug}:v{$version}",
            now()->addMinutes(1),
            fn() => $this->model->where('slug', $slug)->with('vouchers')->first()
        );
        return $campaignVoucher;
    }
    public function store(array $data): CampaignVoucher
    {
        return $this->model->create($data);
    }
    public function update(CampaignVoucher $campaignVoucher, array $data): CampaignVoucher
    {
        $campaignVoucher->update($data);
        return $campaignVoucher;
    }
    public function delete(CampaignVoucher $campaignVoucher): bool
    {
        return $campaignVoucher->delete();
    }
    public function deletePermanent(CampaignVoucher $campaignVoucher): bool
    {
        return $campaignVoucher->deletePermanent();
    }
    public function restore(CampaignVoucher $campaignVoucher): bool
    {
        return $campaignVoucher->restore();
    }
    public function getByTrashed($perPage = 10): LengthAwarePaginator
    {
        $version = $this->versionKey('campaign_vouchers');
        return Cache::remember(
            "voucher:store:list:onlyTrashed:v{$version}",
            now()->addMinutes(5),
            fn() => $this->model->onlyTrashed()->orderBy('updated_at', 'DESC')->paginate($perPage)
        );
    }
    public function findByTrashed(string $id): ?CampaignVoucher
    {
        $version = $this->versionKey('campaign_vouchers');
        $campaignVoucher = Cache::remember("voucher:store:list:campaignVoucherId:{$id}:byTrashed:v{$version}", now()->addMinutes(1), fn() => $this->model->onlyTrashed()->find($id));
        if (!$campaignVoucher) {
            throw new ResourceNotFoundException("CampaignVoucher");
        }
        return $campaignVoucher;
    }
}
