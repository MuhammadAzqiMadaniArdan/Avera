<?php

namespace App\Modules\Location\Contracts;

use App\Modules\Location\Models\District;
use Illuminate\Support\Collection;

interface DistrictServiceInterface
{
    public function get(): Collection;
    public function getByCity(int $cityId): array | Collection;
    public function find(int $id): ?District;
    public function store(array $data): District;
    public function update(array $data, int $id): District;
    public function delete(int $id): bool;
}
