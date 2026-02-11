<?php

namespace App\Modules\Location\Services;

use App\Modules\Location\Contracts\DistrictServiceInterface;
use App\Modules\Location\Models\District;
use App\Modules\Location\Repositories\CityRepository;
use App\Modules\Location\Repositories\DistrictRepository;
use App\Traits\CacheVersionable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DistrictService implements DistrictServiceInterface
{
    use CacheVersionable;
    private string $baseUrl;
    public function __construct(
        private DistrictRepository $districtRepository,
        private CityRepository $cityRepository,
    ) {
        $this->baseUrl = config('rajaongkir.base_url');
    }
    public function get(): Collection
    {
        return $this->districtRepository->get();
    }
    public function getByCity(int $cityId): Collection
    {
        $city = $this->cityRepository->find($cityId);
        $districts = $this->districtRepository->getByCity($cityId);
        if ($districts->isNotEmpty()) {
            return $districts;
        }
        $response = Http::withHeaders([
            'key' => config('rajaongkir.api_key'),
        ])
        ->get($this->baseUrl . "/destination/district/{$city->rajaongkir_id}");

        $data = data_get($response->json(), 'data', []);

        return collect($data)->map(function ($item) use ($cityId) {
            return $this->districtRepository->store([
                'city_id' => $cityId,
                'rajaongkir_id' => $item['id'],
                'name' => $item['name'],
            ]);
        });
    }
    public function getByCityRajaongkir(int $cityId): array | Collection
    {
        $districts = $this->districtRepository->getByCity($cityId);
        if (!empty($districts)) {
            return $districts;
        }
        $response = Http::withHeaders([
            'key' => config('rajaongkir.api_key'),
        ])->get($this->baseUrl . "/district/$cityId");

        return data_get($response->json(), 'data', []);
    }
    public function find(int $id): ?District
    {
        return $this->districtRepository->find($id);
    }
    public function store(array $data): District
    {
        $result = $this->districtRepository->store($data);
        $this->invalidateCache('cities');
        return $result;
    }
    public function update(array $data, int $id): District
    {
        $district = $this->districtRepository->find($id);
        $result = $this->districtRepository->update($district, $data);
        $this->invalidateCache('cities');
        return $result;
    }
    public function delete(int $id): bool
    {
        $district = $this->districtRepository->find($id);
        $this->invalidateCache('cities');

        return $this->districtRepository->delete($district);
    }
}
