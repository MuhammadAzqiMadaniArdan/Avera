<?php

namespace App\Modules\Location\Repositories;

use App\Exceptions\ResourceNotFoundException;
use App\Modules\Location\Contracts\CityRepositoryInterface;
use App\Modules\Location\Models\City;
use App\Traits\CacheVersionable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class CityRepository implements CityRepositoryInterface
{
    use CacheVersionable;
    public function __construct(
        private City $model
    ) {}
    public function get() : Collection
    {
        $version = $this->versionKey('cities');
            $city = Cache::remember("city:list:v{$version}", now()->addHours(1), fn() => $this->model->orderBy('id', 'ASC')->get());
            return $city;
    }
    public function getByProvince(int $provinceId) : Collection
    {
        $version = $this->versionKey('cities');
        $city = Cache::remember("city:list:province_id:{$provinceId}:v{$version}", now()->addHours(1), fn() => $this->model->where('province_id',$provinceId)->orderBy('id', 'ASC')->get());
        return $city;
    }
    public function find(int $id): ?City
    {
        $city = $this->model->find($id);
        return $city;
    }
    public function findOrFail(int $id): ?City
    {
        $city = $this->model->find($id);
        if (!$city) {
            throw new ResourceNotFoundException("City");
        }
        return $city;
    }
    public function store(array $data): City
    {
        return $this->model->create($data);
    }
    public function update(City $city, array $data): City
    {
        $city->update($data);
        return $city;
    }
    public function delete(City $city): bool
    {
        return $city->delete();
    }
}
