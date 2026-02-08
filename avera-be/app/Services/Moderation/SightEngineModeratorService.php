<?php

namespace App\Services\Moderation;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SightEngineModeratorService
{
    public function checkImage(string $imageUrl): array
    {
        // $response = Http::get(config('services.sightengine.url'), [
        //     'url' => $imageUrl,
        //     'models' => 'nudity-2.1,weapon,alcohol,recreational_drug,offensive-2.0,gore-2.0,violence',
        //     'api_user' => config('sightengine.api_user'),
        //     'api_secret' => config('sightengine.api_secret'),
        // ]);

        // if (!$response->successful()) {
        //     Log::error('SightEngine request failed', [
        //         'status' => $response->status(),
        //         'body'   => $response->body(),
        //     ]);

        //     throw new \RuntimeException('SightEngine moderation failed');
        // }

        // $data = $response->json();

        // $nudity = data_get($data, 'nudity', []);

        // return [
        //     'sexual'    => max([
        //         $nudity['sexual_activity'] ?? 0,
        //         $nudity['sexual_display'] ?? 0,
        //         $nudity['erotica'] ?? 0
        //     ]),
        //     'suggestive'    => max([
        //         $nudity['very_suggestive'] ?? 0,
        //         $nudity['suggestive'] ?? 0
        //     ]),
        //     'mild'    => $nudity['mildly_suggestive'] ?? 0,
        //     'weapon'    => $this->maxValue($data['weapon']['classes'] ?? []),
        //     'alcohol'   => data_get($data, 'alcohol.prob', 0),
        //     'drugs'     => data_get($data, 'recreational_drug.prob', 0),
        //     'offensive' => $this->maxValue($data['offensive'] ?? []),
        //     'gore'      => data_get($data, 'gore.prob', 0),
        //     'violence'  => data_get($data, 'violence.prob', 0),
        //     'raw'       => $data
        // ];
        return [
            'sexual'    => 0.99,
            'suggestive'     => 0.99,
            'mild'     => 0.99,
            'weapon'     => 0.99,
            'alcohol'    => 0.99,
            'drugs'      => 0.99,
            'offensive'  => 0.99,
            'gore'      => 0.99,
            'violence'   => 0.99,
            'raw'        => [0.99]
        ];
    }

    private function maxValue(array $values): float
    {
        return empty($values) ? 0 : max(array_map('floatval', Arr::flatten($values)));
    }
}
