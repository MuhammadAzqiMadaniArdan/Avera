<?php

return [
    "public_key" => env("IDENTITY_PUBLIC_KEY"),
    "issuer" => env('IDENTITY_ISSUER','http://127.0.0.1:8000'), // yang menerbitkan token
    "audience" => env('IDENTITY_AUDIENCE','avera-api'), // token digunakan untuk apa
];