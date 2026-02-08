<?php

namespace App\Modules\Location\Repositories;

use App\Exceptions\ResourceNotFoundException;
use App\Modules\Location\Contracts\DistrictRepositoryInterface;
use App\Modules\Location\Models\District;
use App\Traits\CacheVersionable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class DistrictRepository implements DistrictRepositoryInterface
{
    use CacheVersionable;
    public function __construct(
        private District $model
    ) {}
    public function get() : Collection
    {
        $version = $this->versionKey('districts');
            $district = Cache::remember("district:list:v{$version}", now()->addHours(1), fn() => $this->model->orderBy('id', 'ASC')->get());
            return $district;
    }
    public function getByCity(int $cityId) : Collection
    {
        $version = $this->versionKey('districts');
        $district = Cache::remember("district:list:city_id:{$cityId}:v{$version}", now()->addHours(1), fn() => $this->model->where('city_id',$cityId)->get());
        return $district;
    }
    public function find(int $id): ?District
    {
        $district = $this->model->find($id);
        return $district;
    }
    public function findOrFail(int $id): ?District
    {
        $district = $this->model->find($id);
        if (!$district) {
            throw new ResourceNotFoundException("District");
        }
        return $district;
    }
    public function store(array $data): District
    {
        return $this->model->create($data);
    }
    public function update(District $district, array $data): District
    {
        $district->update($data);
        return $district;
    }
    public function delete(District $district): bool
    {
        return $district->delete();
    }
}
