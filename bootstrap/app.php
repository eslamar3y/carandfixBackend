<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->api(prepend: [
            \App\Http\Middleware\ForceJsonResponse::class,
            \App\Http\Middleware\Localization::class,
        ]);

        $middleware->web(append: [
            \App\Http\Middleware\SetLocaleFromSession::class,
        ]);

        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'technician' => \App\Http\Middleware\Technician::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(function () {
            return request()->is('api/*');
        });

        $exceptions->render(function (NotFoundHttpException $e) {
            if (request()->is('api/*')) {
                return response()->json([
                    'error' => true,
                    'message' => 'Resource not found.',
                    'data' => null,
                ], 404);
            }
        });
    })->create();
