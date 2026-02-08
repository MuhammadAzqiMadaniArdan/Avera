<?php

namespace App\Modules\Location\Contracts;

use App\Modules\Location\Models\City;
use Illuminate\Support\Collection;

interface CityServiceInterface
{
    public function get(): Collection;
    public function getByProvince(int $provinceId): Collection;
    public function find(int $id): ?City;
    public function store(array $data): City;
    public function update(array $data, int $id): City;
    public function delete(int $id): bool;
}
