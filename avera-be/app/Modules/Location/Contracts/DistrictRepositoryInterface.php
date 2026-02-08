<?php

namespace App\Modules\Location\Contracts;

use App\Modules\Location\Models\District;
use Illuminate\Support\Collection;

interface DistrictRepositoryInterface
{
    public function get(): Collection;
    public function getByCity(int $cityId): Collection;
    public function find(int $id): ?District;
    public function store(array $data): District;
    public function update(District $cartItem, array $data): District;
    public function delete(District $cartItem): bool;
}
