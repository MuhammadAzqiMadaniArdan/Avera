<?php

namespace App\Modules\Location\Services;

use App\Modules\Location\Contracts\CityServiceInterface;
use App\Modules\Location\Models\City;
use App\Modules\Location\Repositories\CityRepository;
use App\Traits\CacheVersionable;
use Illuminate\Support\Collection;

class CityService implements CityServiceInterface
{
    use CacheVersionable;
    public function __construct(
        private CityRepository $cityRepository,
    ) {}
    public function get() : Collection
    {
        return $this->cityRepository->get();
    } 
    public function getByProvince(int $provinceId) : Collection
    {
        return $this->cityRepository->getByProvince($provinceId);
    } 
    public function find(int $id): ?City
    {
        return $this->cityRepository->find($id);
    }
    public function store(array $data): City
    {
        $result = $this->cityRepository->store($data);
        $this->invalidateCache('cities');
        return $result;
    }
    public function update(array $data, int $id): City
    {
        $city = $this->cityRepository->find($id);
        $result = $this->cityRepository->update($city, $data);
        $this->invalidateCache('cities');
        return $result;
    }
    public function delete(int $id): bool
    {
        $city = $this->cityRepository->find($id);
        $this->invalidateCache('cities');

        return $this->cityRepository->delete($city);
    }
}
