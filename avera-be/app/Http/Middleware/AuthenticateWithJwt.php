<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateWithJwt
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        
        $header = $request->header('Authorization');

        if (!$header || ! str_starts_with($header, 'Bearer ')) {
            abort(401, 'Missing Token');
            return response()->json([
                "message" => "Unauthorize tidak ada header untuk access token"
            ], Response::HTTP_UNAUTHORIZED);
        }

        $jwt = substr($header, 7);
        try {
            $payload = JWT::decode(
                $jwt,
                new Key(file_get_contents(storage_path('oauth-public.key')), 'RS256')
            );
        } catch (\Throwable $e) {
            abort(401, 'Invalid Token');
            return response()->json(["message" => "invalid token"], Response::HTTP_UNAUTHORIZED);
        }

        $uuid = $payload->sub;
        $response = Http::withToken($jwt)->get(config('services.identity.issuer') . "/api/v1/user/{$uuid}");

        if (!$response->ok()) {
            abort(401, "Gagal mengambil email");
        }
        $userData = $response->json();
        $email = $userData['data']['email'] ?? null;

        $request->attributes->set('identity', [
            'id' => $payload->sub,
            'email' => $email,
            'scopes' => $payload->scopes ?? []
        ]);


        return $next($request);
    }
}
