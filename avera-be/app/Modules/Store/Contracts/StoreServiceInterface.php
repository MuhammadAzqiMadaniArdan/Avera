<?php

namespace App\Modules\Store\Contracts;

use App\Modules\Store\Models\Store;
use Illuminate\Pagination\LengthAwarePaginator;

interface StoreServiceInterface
{
    public function get(array $filters): LengthAwarePaginator;
    public function getAdmin(array $filters): LengthAwarePaginator;
    public function getByTrashed(int $perPage): LengthAwarePaginator;
    public function find(string $id): ?Store;
    public function findBySlug(string $slug): ?Store;
    public function findByTrashed(string $id): ?Store;
    public function storeAdmin(array $data): Store;
    public function storeUser(array $data): Store;
    public function update(string $id, array $data): Store;
    public function delete(string $id): bool;
    public function deletePermanent(string $id): bool;
    public function restore(string $id): bool;
}
