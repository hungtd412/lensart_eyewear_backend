<?php

use App\Http\Middleware\CheckAdmin;
use App\Http\Middleware\CheckIdParameter;
use App\Http\Middleware\CheckStatusParameter;
use App\Http\Middleware\CheckThreeIDsParameter;
use App\Http\Middleware\CheckTwoIDsParameter;
use App\Http\Middleware\CheckTypeParameter;
use App\Http\Middleware\CustomGuest;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        // web: __DIR__ . '/../routes/web.php',
        // api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',

        using: function () {
            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/auth.api.php'));

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/user.api.php'));

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/product.api.php'));

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/coupon.api.php'));

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/order.api.php'));

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/blog.api.php'));

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/cart.api.php'));

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/wishlist.api.php'));

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/checkout.api.php'));

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/review.api.php'));

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/banner.api.php'));

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/kafka.api.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'customGuest' => CustomGuest::class,
            'checkIdParameter' => CheckIdParameter::class,
            'checkTwoIdsParameter' => CheckTwoIDsParameter::class,
            'checkThreeIdsParameter' => CheckThreeIDsParameter::class,
            'checkTypeParameter' => CheckTypeParameter::class,
            'checkStatusParameter' => CheckStatusParameter::class,
        ]);
        $middleware->validateCsrfTokens(except: [
            '/*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
    })->create();
