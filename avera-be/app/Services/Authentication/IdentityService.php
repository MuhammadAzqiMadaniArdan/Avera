<?php
namespace App\Services\Authentication;

use GuzzleHttp\Client;

class IdentityService
{
    protected Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'verify' => env('GUZZLE_VERIFY', false),
        ]);
    }

    public function getUser($uuid, $token)
    {
        $response = $this->client->get(config('services.identity.issuer') . "/api/v1/user/{$uuid}", [
            'headers' => [
                'Authorization' => "Bearer $token",
            ],
        ]);

        return json_decode($response->getBody(), true);
    }
}
