<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin.auth'       => \App\Http\Middleware\AdminAuth::class,
            'ctv.auth'         => \App\Http\Middleware\CtvAuth::class,
            'integration.auth' => \App\Http\Middleware\IntegrationAuth::class,
        ]);
        $middleware->web(append: [
            \App\Http\Middleware\TrackVisit::class,
            \App\Http\Middleware\CaptureTomauRef::class,
        ]);
        // Webhook bên ngoài (Viettel Post) không gửi CSRF token
        $middleware->validateCsrfTokens(except: [
            'webhook/viettelpost',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
