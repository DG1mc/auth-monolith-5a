<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \Modules\Auth\Middleware\RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn(Request $request) => $request->is('api/*') || $request->expectsJson(),
        );

        // Langkah 4.9 (sesuai modul): tampilkan InsufficientStockException lewat method render()
        $exceptions->render(function (\Modules\Products\Exceptions\InsufficientStockException $e, $request) {
            return $e->render($request);
        });

        // Log custom exception sebagai peringatan (bukan error) ke storage/logs/laravel.log
        $exceptions->level(\Modules\Products\Exceptions\InsufficientStockException::class, \Psr\Log\LogLevel::WARNING);
        $exceptions->level(\Modules\Library\Exceptions\BookNotAvailableException::class, \Psr\Log\LogLevel::WARNING);
        $exceptions->level(\Modules\Library\Exceptions\AuthorHasBooksException::class, \Psr\Log\LogLevel::WARNING);
    })->create();
