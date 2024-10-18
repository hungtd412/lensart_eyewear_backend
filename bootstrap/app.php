<?php

use App\Http\Middleware\CustomGuest;
use App\Http\Middleware\VerifyCsrfTokenCustom;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',

        using: function () {
            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            Route::middleware('web')
                ->group(base_path('routes/auth.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(CustomGuest::class);

        $middleware->alias([
            'customGuest' => CustomGuest::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->report(function (TokenMismatchException $e) {
            return response()->json(['message' => 'CSRF token không hợp lệ.'], 419);
        });
    })->create();
