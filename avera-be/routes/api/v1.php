<?php

use App\Http\Controllers\AuthController;
use App\Modules\Cart\Http\Controllers\CartController;
use App\Modules\Category\Http\Controllers\CategoryController;
use App\Modules\Checkout\Http\Controllers\CheckoutController;
use App\Modules\Location\Http\Controllers\CityController;
use App\Modules\Location\Http\Controllers\DistrictController;
use App\Modules\Location\Http\Controllers\ProvinceController;
use App\Modules\Order\Http\Controllers\OrderController;
use App\Modules\Order\Http\Controllers\ReviewController;
use App\Modules\Order\Http\Controllers\ShipmentController;
use App\Modules\Product\Http\Controllers\ProductController;
use App\Modules\Product\Http\Controllers\ProductImageController;
use App\Modules\Product\Http\Controllers\ProductSellerController;
use App\Modules\Store\Http\Controllers\StoreController;
use App\Modules\User\Http\Controllers\UserAddressController;
use App\Modules\User\Http\Controllers\UserController;
use App\Modules\Voucher\Http\Controllers\UserVoucherController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('isGuest')->group(function () {
    Route::prefix('product')->name('product.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::get('/random', [ProductController::class, 'indexRandom'])->name('index.random');
        Route::get('/top', [ProductController::class, 'indexTop'])->name('top');
        Route::get('/{compound}', [ProductController::class, 'show'])->name('show');
        Route::get('/{storeId}/store', [ProductController::class, 'IndexByStore'])->name('store');
        Route::get('/{categoryId}/category', [ProductController::class, 'IndexByCategory'])->name('category');
        Route::get('/{productId}/reviews', [ReviewController::class, 'getByProduct'])->name('review');
    });
    Route::prefix('category')->name('category.')->group(function () {
        Route::get('/', [CategoryController::class, 'indexParent'])->name('index.parent');
        Route::get('/tree', [CategoryController::class, 'indexTree'])->name('index.tree');
        Route::get('/{compound}/products', [ProductController::class, 'indexByStore'])->name('products');
    });
    Route::prefix('store')->name('store.')->group(function () {
        Route::get('/{slug}', [StoreController::class, 'show'])->name('show');
        Route::get('/{slug}/products', [ProductController::class, 'indexbyStore'])->name('products');
    });
    Route::prefix('courier')->name('courier.')->group(function () {
        Route::get('/sla', [ShipmentController::class, 'indexCourierSla'])->name('sla');
    });
});
Route::middleware(['oauth.jwt', 'ensure.user', 'throttle:api'])->group(function () {
    Route::prefix('provinces')->name('provinces.')->group(function () {
        Route::get('/', [ProvinceController::class, 'index'])->name('index');
    });
    Route::prefix('cities')->name('cities.')->group(function () {
        Route::get('/', [CityController::class, 'index'])->name('index');
        Route::get('/{provinceId}', [CityController::class, 'indexByProvince'])->name('index.province');
    });
    Route::prefix('districts')->name('districts.')->group(function () {
        Route::get('/', [DistrictController::class, 'index'])->name('index');
        Route::get('/{cityId}', [DistrictController::class, 'indexByCity'])->name('index.city');
    });
    Route::prefix('user')->name('profile.')->group(function () {
        Route::get('/profile', [UserController::class, 'getProfile'])->name('index');
        Route::patch('/profile', [UserController::class, 'updateProfile'])->name('update');
        Route::post('/profile/avatar', [UserController::class, 'uploadAvatar'])->name('update');
        Route::prefix('address')->name('address.')->group(function () {
            Route::get('/', [UserAddressController::class, 'index'])->name('index');
            Route::post('/', [UserAddressController::class, 'store'])->name('store');
            Route::patch('/{id}', [UserAddressController::class, 'update'])->name('update');
            Route::delete('/{id}', [UserAddressController::class, 'destroy'])->name('destroy');
        });
    });
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/', [CartController::class, 'store'])->name('store');
        Route::patch('/{id}', [CartController::class, 'update'])->name('update');
        Route::delete('/', [CartController::class, 'delete'])->name('delete');
        Route::delete('/', [CartController::class, 'flush'])->name('flush');
    });
    Route::prefix('category')->name('category.')->group(function () {
        Route::get('/{id}', [CategoryController::class, 'show'])->name('show');
        Route::post('/', [CategoryController::class, 'store'])->name('store');
    });
    Route::prefix('product')->name('product.')->group(function () {
        Route::post('/', [ProductController::class, 'store'])->name('store');
        Route::patch('/', [ProductController::class, 'update'])->name('update');
        Route::delete('/', [ProductController::class, 'destroy'])->name('destroy');
        Route::prefix('image')->name('image.')->middleware('throttle:image_upload')->group(function () {
            Route::post('/images', [ProductImageController::class, 'store'])->name('store');
            Route::patch('/{productImageId}/images', [ProductImageController::class, 'replace'])->name('update');
            Route::delete('/{productImageId}/images', [ProductImageController::class, 'destroy'])->name('destroy');
        });
    });

    Route::prefix('store')->name('store.')->group(function () {
        Route::get('/', [StoreController::class, 'index'])->name('index');
        Route::get('/{slug}/products', [ProductController::class, 'indexbyStore'])->name('products');
        Route::get('/{slug}', [StoreController::class, 'show'])->name('show');
        Route::post('/admin', [StoreController::class, 'storeAdmin'])->name('store');
        Route::post('/', [StoreController::class, 'storeUser'])->name('store.user');
        Route::post('/{id}/verification', [StoreController::class, 'sendVerificationStore'])->name('verify');
        Route::post('/images', [ProductImageController::class, 'store'])->name('image.store');
    });
    Route::prefix('review')->name('review.')->group(function () {
        Route::get('/', [ReviewController::class, 'index'])->name('index');
        Route::get('/{id}', [ReviewController::class, 'show'])->name('show');
        Route::post('/', [ReviewController::class, 'store'])->name('store');
    });
    Route::prefix('voucher')->name('voucher.')->group(function () {
        Route::get('/', [UserVoucherController::class, 'index'])->name('index');
        Route::get('/{id}', [UserVoucherController::class, 'show'])->name('show');
        Route::get('/store/{storeId}', [UserVoucherController::class, 'showByStore'])->name('show.store');
        Route::post('/campaign-voucher/claim', [UserVoucherController::class, 'claimCampaign'])->name('campaign.claim');
        Route::post('/store-voucher/claim', [UserVoucherController::class, 'claimStore'])->name('store.claim');
    });
    Route::prefix('checkout')->name('checkout.')->group(function () {
        Route::get('/', [CheckoutController::class, 'index'])->name('get');
        Route::post('/', [CheckoutController::class, 'store'])->name('store');
        Route::patch('/{id}', [CheckoutController::class, 'update'])->name('update');
        Route::delete('/', [CheckoutController::class, 'destroy'])->name('destroy');
        Route::post('/{checkoutId}/place-order', [OrderController::class, 'store'])->name('order');
    });
    Route::prefix('order')->name('order.')->group(function () {
        Route::get('/', [OrderController::class, 'indexPurchase'])->name('get');
        Route::get('/{id}', [OrderController::class, 'show'])->name('show');
        Route::get('/item/{id}', [OrderController::class, 'showItem'])->name('show.item');
        Route::post('/', [OrderController::class, 'store'])->name('store');
        Route::patch('/{id}', [OrderController::class, 'update'])->name('update');
        Route::delete('/', [OrderController::class, 'destroy'])->name('destroy');
        Route::post('/payment/callback', [OrderController::class, 'paymentCallback'])->name('payment.callbackn');
    });
    Route::middleware('isSeller')->prefix('seller')->name('seller.')->group(function () {
        Route::prefix('product')->name('product.')->group(function () {
            Route::get('/', [ProductSellerController::class, 'index'])->name('index');
            Route::get('/{compound}', [ProductSellerController::class, 'show'])->name('show');
            Route::post('/', [ProductSellerController::class, 'store'])->name('store');
            Route::patch('/', [ProductSellerController::class, 'draft'])->name('draft');
            Route::patch('/', [ProductSellerController::class, 'publish'])->name('publish');
            Route::delete('/', [ProductSellerController::class, 'destroy'])->name('destroy');
            Route::prefix('image')->name('image.')->middleware('throttle:image_upload')->group(function () {
                Route::post('/images', [ProductImageController::class, 'store'])->name('store');
                Route::patch('/{productImageId}/images', [ProductImageController::class, 'replace'])->name('update');
                Route::delete('/{productImageId}/images', [ProductImageController::class, 'destroy'])->name('destroy');
            });
        });
        Route::prefix('store')->name('store.')->group(function () {
            Route::get('/', [StoreController::class, 'indexBySeller'])->name('index');
        });
    });
    Route::middleware('isAdmin')->prefix('admin')->name('admin.')->group(function () {
        Route::prefix('category')->name('category.')->group(function () {
            Route::get('/', [CategoryController::class, 'indexAdmin'])->name('index');
            Route::get('/trashed', [CategoryController::class, 'indexByTrashed'])->name('index.trashed');
            Route::get('/{id}', [CategoryController::class, 'showById'])->name('read');
            Route::get('/{id}', [CategoryController::class, 'showByTrashed'])->name('read.trashed');
            Route::post('/', [CategoryController::class, 'storeAdmin'])->name('store');
            Route::patch('/{id}', [CategoryController::class, 'updateAdmin'])->name('update');
            Route::patch('/{id}/restore', [CategoryController::class, 'restore'])->name('restore');
            Route::delete('/{id}', [CategoryController::class, 'destroyAdmin'])->name('destroy');
            Route::delete('/{id}/force', [CategoryController::class, 'destroyPermanent'])->name('destroy.permanent');
        });
        Route::prefix('product')->name('product.')->group(function () {
            Route::get('/', [CategoryController::class, 'indexAdmin'])->name('index');
            Route::get('/trashed', [CategoryController::class, 'indexByTrashed'])->name('index.trashed');
            Route::post('/', [CategoryController::class, 'storeAdmin'])->name('store');
            Route::patch('/{id}', [CategoryController::class, 'updateAdmin'])->name('update');
            Route::patch('/{id}/restore', [CategoryController::class, 'restore'])->name('restore');
            Route::delete('/{id}', [CategoryController::class, 'destroyAdmin'])->name('destroy');
            Route::delete('/{id}/force', [CategoryController::class, 'destroyPermanent'])->name('destroy.permanent');
        });
        Route::prefix('store')->name('store.')->group(function () {
            Route::get('/', [StoreController::class, 'indexAdmin'])->name('index');
            Route::get('/trashed', [StoreController::class, 'indexByTrashed'])->name('index.trashed');
            Route::post('/', [StoreController::class, 'storeAdmin'])->name('store');
            Route::name('verify.')->group(function () {
                Route::post('/{id}/verified', [StoreController::class, 'verifiedStoreAdmin'])->name('verified');
                Route::post('/{id}/rejected', [StoreController::class, 'rejectedStoreAdmin'])->name('rejected');
                Route::post('/{id}/suspended', [StoreController::class, 'suspendedStoreAdmin'])->name('suspended');
            });
            Route::patch('/{id}', [StoreController::class, 'updateAdmin'])->name('update');
            Route::patch('/{id}/restore', [StoreController::class, 'restore'])->name('restore');
            Route::delete('/{id}', [StoreController::class, 'destroyAdmin'])->name('destroy');
            Route::delete('/{id}/force', [StoreController::class, 'destroyPermanent'])->name('destroy.permanent');
        });
    });
});
