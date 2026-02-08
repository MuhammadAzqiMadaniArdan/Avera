<?php

namespace App\Modules\Location\Contracts;

use App\Modules\Location\Models\Province;
use Illuminate\Support\Collection;

interface ProvinceServiceInterface
{
     public function get() : Collection;
    public function find(int $id): ?Province;
    public function store(array $data): Province;
    public function update(array $data,int $id): Province;
    public function delete(int $id): bool;
}
