<?php

return [
    'api_user' => env('SIGHTENGINE_API_USER'),
    'api_secret' => env('SIGHTENGINE_API_SECRET'),
    'url' => env('SIGHTENGINE_URI'),
    'models' => [
        'nudity',
        'wad',
        'violence',
        'self-harm',
        'gore',
        'ai-generated',
    ],
    'thresholds' => [
        'nudity' => 0.7,
        'violence' => 0.6,
        'gore' => 0.4,
        'ai-generated' => 0.8,
    ],
    'fallback' => 'review',
    'timeout' => 10,
];
