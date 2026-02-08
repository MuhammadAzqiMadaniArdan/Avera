<?php

namespace App\Modules\Voucher\Contracts;

use App\Modules\Voucher\Models\UserVoucher;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserVoucherServiceInterface
{
    public function get(int $perPage): LengthAwarePaginator;
    public function getByTrashed(int $perPage): LengthAwarePaginator;
    public function find(string $id): ?UserVoucher;
    public function findByTrashed(string $id): ?UserVoucher;
    public function store(array $data): UserVoucher;
    public function update(string $id, array $data): UserVoucher;
    public function delete(string $id): bool;
    public function deletePermanent(string $id): bool;
    public function restore(string $id): bool;
}
