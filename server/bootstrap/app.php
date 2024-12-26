<?php

use App\Http\Middleware\CheckOrigin;
use App\Http\Middleware\CheckPermission;
use App\Support\HttpResponse;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->statefulApi();

        $middleware->alias([
            'check.permission' => CheckPermission::class,
            'check.origin' => CheckOrigin::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->renderable(function (NotFoundHttpException $exception) {
            return HttpResponse::error(['not_found' => $exception->getMessage()], 404);
        });

        $exceptions->renderable(function (QueryException $e) {
            return HttpResponse::error(['query' => 'An error occurred while trying to communicate with the database. Please try again'], 500);
        });
    })->create();
