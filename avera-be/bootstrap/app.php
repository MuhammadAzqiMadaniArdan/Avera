<?php

use App\Exceptions\BusinessException;
use App\Helpers\ApiResponse;
use App\Helpers\AuthHelper;
use App\Http\Middleware\AuthenticateWithJwt;
use App\Http\Middleware\AuthenticateWithOauth;
use App\Http\Middleware\EnsureLocalExist;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsGuest;
use App\Http\Middleware\IsSeller;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //  $middleware->trustProxies(
        //     at: '*',
        //     headers: Request::HEADER_X_FORWARDED_FOR
        //     | Request::HEADER_X_FORWARDED_HOST 
        //     | Request::HEADER_X_FORWARDED_PORT
        //     | Request::HEADER_X_FORWARDED_PROTO
        // );
        $middleware->alias([
            "oauth.jwt" => AuthenticateWithJwt::class,
            "ensure.user" => EnsureLocalExist::class,
            "isAdmin" => IsAdmin::class,
            "isSeller" => IsSeller::class,
            "isGuest" => IsGuest::class,
        ]);
        
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (
            BusinessException $e,
            $request
        ) {
            return ApiResponse::errorResponse($e->getMessage(), $e->statusCode());
        });

        $exceptions->render(function (
            ValidationException $e,
            $request
        ) {
            return ApiResponse::errorResponse($e->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY, $e->errors());
        });

        // $exceptions->render(function(
        //     Throwable $e,$request
        // ){
        //     report($e);
        //     return ApiResponse::errorResponse('Internal Server Error',Response::HTTP_INTERNAL_SERVER_ERROR,[$e]);
        // });
    })->create();
