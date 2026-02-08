<?php

namespace App\Http\Middleware;

use App\Helpers\AuthHelper;
use App\Modules\User\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony    \Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $uuid = AuthHelper::uuid($request);
        $user = User::query()->where('identity_core_id', $uuid)->first();
        if ($user->role != "admin") {
            abort(401, "Kamu Bukan Admin");
        }
        return $next($request);
    }
}
