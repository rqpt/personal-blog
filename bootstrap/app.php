<?php

use App\Http\Middleware\HealUrl;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Torchlight\Middleware\RenderTorchlight;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->validateCsrfTokens(except: [
            '*',
        ]);
        $middleware->trustProxies([
            '*',
        ]);
        $middleware->prepend(RenderTorchlight::class);
        $middleware->alias([
            'heal-url' => HealUrl::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
