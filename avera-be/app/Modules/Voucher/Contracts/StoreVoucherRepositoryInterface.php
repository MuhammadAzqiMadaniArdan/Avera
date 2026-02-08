<?php

namespace App\Modules\Voucher\Contracts;

use App\Modules\Voucher\Models\StoreVoucher;
use Illuminate\Pagination\LengthAwarePaginator;

interface StoreVoucherRepositoryInterface {
        public function get(int $perPage) : LengthAwarePaginator; 
    public function getByTrashed(int $perPage) : LengthAwarePaginator; 
    public function find(string $id) : ?StoreVoucher; 
    public function findByTrashed(string $id) : ?StoreVoucher; 
    public function store(array $data) : StoreVoucher; 
    public function update(StoreVoucher $storeVoucher,array $data) : StoreVoucher; 
    public function delete(StoreVoucher $storeVoucher) : bool; 
    public function deletePermanent(StoreVoucher $storeVoucher) : bool; 
    public function restore(StoreVoucher $storeVoucher) : bool; 
}