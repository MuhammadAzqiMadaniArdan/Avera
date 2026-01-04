<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function redirectToIdentity()
    {
        $codeVerifier = Str::random(64);

        session([
            'pkce_verifier' => $codeVerifier
        ]);

        $codeChallenge = rtrim(strtr(
            base64_encode(hash('sha256',$codeVerifier,true)),'+/','-_'
        ),'=');

        $query = http_build_query([
            'client_id' => config('services.identity.client_id'),
            'redirect_uri' => config('services.identity.redirect_uri'),
            'response_type' => 'code',
            'scope' => '',
            'code_challenge' => $codeChallenge,
            'code_challenge_method' => 'S256'
        ]);

        return redirect(config('services.identity.base_url').'/oauth/authorize?'.$query);
    }

    public function callback(Request $request)
    {
        $code = $request->query('code');
        $response = Request::create(config('services.identity.base_url').'/oauth/token','POST',
        [
            'grant_type' => 'authorization_code',
            'client_id' => config('services.identity.client_id'),
            'redirect_uri' => config('services.identity.redirect_uri'),
            'code' => $code,
            'code_verifier' => session('pkce_verifier'),
        ]);

        abort_if($response->failed(),401);

        $token = $response->json();

        session([
            'access_token' => $token['access_token'],
            'refresh_token' => $token['refresh_token'],
            'expires_at' => now()->addSeconds($token['expires_in']),
        ]);

        return redirect(config('app.frontend_url').'/dashboard');
    }

    public function logout()
    {
        session()->invalidate();
        return redirect(config('app.frontend_url'));
    }
}
