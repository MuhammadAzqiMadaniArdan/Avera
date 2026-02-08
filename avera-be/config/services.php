<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'identity' =>  [
        'client_id' => env('AVERA_BE_CLIENT_ID'),
        'client_secret' => env('AVERA_BE_CLIENT_SECRET'),
        'issuer' => env('IDENTITY_ISSUER', 'http://127.0.0.1:8000'), // yang menerbitkan token
        'public_key' => env('IDENTITY_PUBLIC_KEY'),
        'audience' => env('IDENTITY_AUDIENCE', 'avera-api'), // token digunakan untuk apa
    ],

    'google' => [
        'vision_credentials' => env('GOOGLE_APPLICATION_CREDENTIALS')
    ],

];
