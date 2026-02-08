<?php

namespace App\Modules\Voucher\Contracts;

use App\Modules\Voucher\Models\UserVoucher;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserVoucherRepositoryInterface {
        public function get(int $perPage) : LengthAwarePaginator; 
    public function getByTrashed(int $perPage) : LengthAwarePaginator; 
    public function find(string $id) : ?UserVoucher; 
    public function findByTrashed(string $id) : ?UserVoucher; 
    public function store(array $data) : UserVoucher; 
    public function update(UserVoucher $userVoucher,array $data) : UserVoucher; 
    public function delete(UserVoucher $userVoucher) : bool; 
    public function deletePermanent(UserVoucher $userVoucher) : bool; 
    public function restore(UserVoucher $userVoucher) : bool; 
}