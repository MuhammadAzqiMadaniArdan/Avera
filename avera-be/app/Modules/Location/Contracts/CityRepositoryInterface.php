<?php

namespace App\Modules\Location\Contracts;

use App\Modules\Location\Models\City;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface CityRepositoryInterface
{
    public function get(): Collection;
    public function getByProvince(int $provinceId): Collection;
    public function find(int $id): ?City;
    public function store(array $data): City;
    public function update(City $cartItem, array $data): City;
    public function delete(City $cartItem): bool;
}
