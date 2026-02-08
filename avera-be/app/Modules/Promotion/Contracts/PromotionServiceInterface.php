<?php

namespace App\Modules\Promotion\Contracts;

use App\Modules\Promotion\Models\Promotion;
use Illuminate\Pagination\LengthAwarePaginator;

interface PromotionServiceInterface
{
    public function get(int $perPage): LengthAwarePaginator;
    public function getByTrashed(int $perPage): LengthAwarePaginator;
    public function find(string $id): ?Promotion;
    public function findByTrashed(string $id): ?Promotion;
    public function store(array $data,string $userId): Promotion;
    public function update(string $id, array $data): Promotion;
    public function delete(string $id): bool;
    public function deletePermanent(string $id): bool;
    public function restore(string $id): bool;
}
