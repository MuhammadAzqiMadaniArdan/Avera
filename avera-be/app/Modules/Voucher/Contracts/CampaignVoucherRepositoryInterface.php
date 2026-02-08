<?php

namespace App\Modules\Voucher\Contracts;

use App\Modules\Voucher\Models\CampaignVoucher;
use Illuminate\Pagination\LengthAwarePaginator;

interface CampaignVoucherRepositoryInterface {
        public function get(int $perPage) : LengthAwarePaginator; 
    public function getByTrashed(int $perPage) : LengthAwarePaginator; 
    public function find(string $id) : ?CampaignVoucher; 
    public function findByTrashed(string $id) : ?CampaignVoucher; 
    public function store(array $data) : CampaignVoucher; 
    public function update(CampaignVoucher $storeVoucher,array $data) : CampaignVoucher; 
    public function delete(CampaignVoucher $storeVoucher) : bool; 
    public function deletePermanent(CampaignVoucher $storeVoucher) : bool; 
    public function restore(CampaignVoucher $storeVoucher) : bool; 
}