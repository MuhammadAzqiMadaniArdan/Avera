<?php

namespace App\Modules\Order\Services;

use App\Modules\Order\Repositories\ShipmentRepository;
use Illuminate\Support\Facades\Http;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class ShipmentService
{
    protected string $apiKey;
    protected string $baseUrl;

    public function __construct(
        private ShipmentRepository $shipmentRepository,
    ) {

        $this->apiKey  = config('rajaongkir.api_key');
        $this->baseUrl = config('rajaongkir.base_url');

    }


    /**
     * Hitung ongkir (return semua service)
     */
    public function calculate(
        int $originDistrictId,
        int $destinationDistrictId,
        int $weight,
        string $courier
    ): array {

        if (!$originDistrictId || !$destinationDistrictId) {
            return [];
        }

        $weight = max(1, $weight);

        $response = Http::asForm()
            ->withHeaders([
                'key' => $this->apiKey,
            ])
            ->post(
                $this->baseUrl . '/calculate/district/domestic-cost',
                [
                    'origin'      => (string) $originDistrictId,
                    'destination' => (string) $destinationDistrictId,
                    'weight'      => (int) $weight,
                    'courier'     => $courier,
                ]
            );

        if (!$response->successful()) {
            Log::warning('Courier not available', [
                'courier' => $courier,
                'origin' => $originDistrictId,
                'destination' => $destinationDistrictId,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            return [];
        }

        $services = data_get($response->json(), 'data', []);

        if (empty($services)) {
            return [];
        }

        return collect($services)->map(function ($service) {
            [$minDays, $maxDays] = $this->parseEtd($service['etd'] ?? null);

            return [
                'courier_code' => $service['code'],
                'courier_name' => $service['name'],
                'service'      => $service['service'],
                'description'  => $service['description'] ?? '',
                'cost'         => $service['cost'] ?? 0,
                'etd_raw'      => $service['etd'] ?? null,
                'min_days'     => $minDays,
                'max_days'     => $maxDays,
            ];
        })->values()->toArray();
    }

    private function parseEtd(?string $etd): array
    {
        if (!$etd) {
            return [null, null];
        }

        // ambil angka saja
        preg_match_all('/\d+/', $etd, $matches);

        if (empty($matches[0])) {
            return [null, null];
        }

        $numbers = array_map('intval', $matches[0]);

        return count($numbers) === 1
            ? [$numbers[0], $numbers[0]]
            : [$numbers[0], $numbers[1]];
    }


    public function calculateDummy(
        int $originCityId,
        int $destinationCityId,
        int $weight,
        string $courier
    ): array {
        // simulasi realistis
        return [
            [
                'courier_code' => $courier,
                'courier_name' => strtoupper($courier),
                'service' => 'REG',
                'description' => "ini $courier",
                'cost' => 18000,
                'etd_raw' => "2-3",
                'min_days' => 2,
                'max_days' => 3,
            ],
            [
                'courier_code' => $courier,
                'courier_name' => strtoupper($courier),
                'service' => 'YES',
                'description' => "ini $courier",
                'cost' => 32000,
                'etd_raw' => "2-3",
                'min_days' => 2,
                'max_days' => 3,
            ],
        ];
    }

    public function getCouriers(): array
    {
        return ['jne', 'tiki', 'pos'];
    }

    public function getCourier(): Collection
    {
        return $this->shipmentRepository->getCourier();
    }
}
