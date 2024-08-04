<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;

use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        apiPrefix: 'api',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withSchedule(function (Schedule $schedule) {
        $schedule->command('messages:clean-expired')->everyMinute();
    })
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(\App\Http\Middleware\V1\LanguageMiddleware::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->renderable(function (MethodNotAllowedHttpException $e) {
            return response()->json([
                'status' => false,
                'message' => __('app.notFound'),
                // 'details' => $e->getMessage(),
            ], 404);
        });
    })->create();