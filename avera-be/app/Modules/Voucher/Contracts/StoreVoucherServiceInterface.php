<?php

namespace App\Modules\Voucher\Contracts;

use App\Modules\Voucher\Models\StoreVoucher;
use Illuminate\Pagination\LengthAwarePaginator;

interface StoreVoucherServiceInterface
{
    public function get(int $perPage): LengthAwarePaginator;
    public function getByTrashed(int $perPage): LengthAwarePaginator;
    public function find(string $id): ?StoreVoucher;
    public function findByTrashed(string $id): ?StoreVoucher;
    public function store(array $data): StoreVoucher;
    public function update(string $id, array $data): StoreVoucher;
    public function delete(string $id): bool;
    public function deletePermanent(string $id): bool;
    public function restore(string $id): bool;
}
