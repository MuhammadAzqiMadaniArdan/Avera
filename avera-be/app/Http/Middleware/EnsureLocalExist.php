<?php

namespace App\Http\Middleware;

use App\Helpers\AuthHelper;
use App\Modules\User\Models\User;
use App\Traits\CacheVersionable;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class EnsureLocalExist
{
    use CacheVersionable;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $uuid = AuthHelper::uuid($request);
        $email = AuthHelper::email($request);

        if (!$uuid) {
            abort(401);
        }
        
        $user = User::firstOrCreate(['identity_core_id' => $uuid], [
            'identity_core_id' => $uuid,
            'email' => $email,
            'name' => Str::random(16),
            'username' => Str::random(16),
        ]);

        if ($user) {
            Auth::setUser($user);
        }
        return $next($request);
    }
}
