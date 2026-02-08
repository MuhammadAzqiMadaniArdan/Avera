<?php

namespace App\Modules\Location\Contracts;

use App\Modules\Location\Models\Province;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ProvinceRepositoryInterface
{
    public function get(): Collection;
    public function find(int $id): ?Province;
    public function store(array $data): Province;
    public function update(Province $province, array $data): Province;
    public function delete(Province $province): bool;
}
