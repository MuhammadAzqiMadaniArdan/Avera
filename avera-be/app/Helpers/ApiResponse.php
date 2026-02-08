<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ApiResponse
{
    public static function successResponse(
        mixed $data = [],
        string $message = "Success",
        int $code = Response::HTTP_OK,
        array $meta = []
    ): JsonResponse | Response {
        if ($code === Response::HTTP_NO_CONTENT) {
            return response()->noContent();
        }
        $response = [
            "success" => true,
            "data" => $data,
            "message" => $message,
            "code" => $code
        ];

        if (!empty($meta)) {
            $response['meta'] = $meta;
        }

        return response()->json($response, $code);
    }

    public static function errorResponse(
        string $message = 'Error',
        int $code = Response::HTTP_INTERNAL_SERVER_ERROR,
        array $errors = []
    ): JsonResponse {
        $response = [
            "success" => false,
            "message" => $message,
        ];

        if (!empty($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }
}
