<?php

namespace App\Modules\Location\Repositories;

use App\Exceptions\ResourceNotFoundException;
use App\Modules\Location\Contracts\ProvinceRepositoryInterface;
use App\Modules\Location\Models\Province;
use App\Traits\CacheVersionable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class ProvinceRepository implements ProvinceRepositoryInterface
{
    use CacheVersionable;
    public function __construct(
        private Province $model
    ) {}
    public function get(): Collection
    {
        $version = $this->versionKey('provinces');
        $provinces = Cache::remember("provinces:list:v{$version}", now()->addHours(1), fn() => $this->model->orderBy('id', 'ASC')->get());
        return $provinces;
    }
    public function find(int $id): ?Province
    {
        $province = $this->model->find($id);
        return $province;
    }
    public function findOrFail(string $id): ?Province
    {
        $province = $this->model->with('product')->find($id);
        if (!$province) {
            throw new ResourceNotFoundException("Cart item");
        }
        return $province;
    }
    public function store(array $data): Province
    {
        return $this->model->create($data);
    }
    public function update(Province $province, array $data): Province
    {
        $province->update($data);
        return $province;
    }
    public function delete(Province $province): bool
    {
        return $province->delete();
    }
}
