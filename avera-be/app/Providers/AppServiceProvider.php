<?php

namespace App\Providers;

use App\Exceptions\ResourceNotFoundException;
use App\Helpers\AuthHelper;
use App\Modules\Cart\Models\CartItem;
use App\Modules\Cart\Policies\CartPolicy;
use App\Modules\Checkout\Models\Checkout;
use App\Modules\Checkout\Policies\CheckoutPolicy;
use App\Modules\Product\Models\Product;
use App\Modules\Product\Policies\ProductPolicy;
use App\Modules\Store\Models\Store;
use App\Modules\Store\Policies\StorePolicy;
use App\Modules\User\Models\User;
use App\Modules\User\Models\UserAddress;
use App\Modules\User\Policies\UserAddressPolicy;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Midtrans\Config;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void {}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(AuthHelper::uuid($request) ?? $request->ip());
        });

        RateLimiter::for('auth', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });

        RateLimiter::for('image-upload', function (Request $request) {
            return Limit::perMinute(10)
                ->by(AuthHelper::uuid($request))
                ->response(
                    fn() =>
                    response()->json([
                        'message' => 'Terlalu banyak upload.coba lagi nanti'
                    ], Response::HTTP_TOO_MANY_REQUESTS)
                );
        });

        RateLimiter::for('report',function (Request $request) {
            return Limit::perMinute(5)
            ->by(AuthHelper::uuid($request));
        });
        
        Gate::policy(CartItem::class, CartPolicy::class);
        Gate::policy(Store::class, StorePolicy::class);
        Gate::policy(Product::class, ProductPolicy::class);
        Gate::policy(Checkout::class, CheckoutPolicy::class);
        Gate::policy(UserAddress::class, UserAddressPolicy::class);

        $this->app->singleton(Authenticatable::class, function ($app) {
            $request = $app->make(Request::class);
            $uuid = AuthHelper::uuid($request);
            $user = User::where('identity_core_id', $uuid)->first();
            if(!$user) return null;
            return $user;
        });

        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }
}
