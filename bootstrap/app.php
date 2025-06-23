<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Illuminate\Auth\Access\AuthorizationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->renderable(function (AccessDeniedHttpException $e, $request): JsonResponse {
            return response()->json([
                'message' => 'You are not authorized to perform this action.',
            ], 403);
        });
        $exceptions->renderable(function (AuthorizationException $e, Request $request): JsonResponse {
            return response()->json([
                'message' => 'You are not authorized to perform this action.',
            ], 403);
        });
    })->create();
