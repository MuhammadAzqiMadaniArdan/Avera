<?php
return [
    'api_key' => env('RAJAONGKIR_API_KEY'),
    'account_type' => env('RAJAONGKIR_ACCOUNT_TYPE', 'starter'), 
    'base_url' => env('RAJAONGKIR_BASE_URL', 'https://rajaongkir.komerce.id/api/v1'),
    'timeout' => 10,
    'default_origin_city_id' => 152, // Jakarta Selatan
    'couriers' => [
        [
            'name' => 'JNE',
            'code' => 'jne',
            'services' => ['REG', 'YES', 'OKE'],
        ],
        [
            'name' => 'J&T',
            'code' => 'jnt',
            'services' => ['REG'],
        ],
        [
            'name' => 'SiCepat',
            'code' => 'sicepat',
            'services' => ['GOKIL', 'REG'],
        ],
    ],
];
