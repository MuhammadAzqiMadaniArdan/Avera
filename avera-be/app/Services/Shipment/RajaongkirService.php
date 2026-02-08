<?php
namespace App\Services\Shipment;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Exception;

class RajaOngkirService
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('rajaongkir.base_url');
    }

    public function getCitiesByProvince(int $provinceId): array
    {
        $response = Http::withHeaders([
            'key' => config('rajaongkir.api_key'),
        ])->get(
            "https://rajaongkir.komerce.id/api/v1/destination/city/{$provinceId}"
        );

        if (!$response->successful()) {
            throw new \Exception('Failed fetch city');
        }

        return $response->json('data');
    }

    protected function request(string $endpoint, array $params = [])
    {
        $response = Http::timeout(5)
            ->retry(2, 200)
            ->withHeaders([
                'key' => config('services.rajaongkir.key'),
            ])
            ->get($this->baseUrl . $endpoint, $params);

        if (! $response->successful()) {
            throw new Exception('RajaOngkir API error');
        }

        return $response->json('rajaongkir.results');
    }

    public function getCity(int $cityId): array
    {
        return Cache::remember(
            "rajaongkir:city:$cityId",
            now()->addDay(),
            fn () => $this->request('/city', ['id' => $cityId])
        );
    }
}
