<?php

use App\Http\Middleware\JwtMiddleware;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
        $middleware->alias([
            'jwt' => JwtMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {

        // 1. Validation error (422)
        $exceptions->render(function (ValidationException $e, Request $request) {
            return response()->json([
                'path' => $request->path(),
                'method' => $request->method(),
                'status'  => 422,
                'message' => 'Invalid input data.',
                'errors'  => $e->errors(),
            ], 422);
        });

        // 2. Authentication error/JWT (401)
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            return response()->json([
                'path' => $request->path(),
                'method' => $request->method(),
                'status'  => 401,
                'message' => 'Not authorized. Invalid or missing token.',
            ], 401);
        });

        // 3. Resource not found (404)
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            return response()->json([
                'path' => $request->path(),
                'method' => $request->method(),
                'status'  => 404,
                'message' => 'Resource not found.',
            ], 404);
        });

        // 4. Generic error
        $exceptions->render(function (Throwable $e, Request $request) {
            if ($request->is('api/*')) {

                $status = match (true) {
                    method_exists($e, 'getStatusCode') => $e->getStatusCode(),
                    property_exists($e, 'status') => $e->status,
                    default => 500
                };

                $message = $e->getMessage();
                if ($status == 500 && config('app.env') === 'production') {
                    $message = 'An unexpected internal server error occurred.';
                }

                return response()->json([
                    'path'   => $request->path(),
                    'method' => $request->method(),
                    'status' => $status,
                    'message'=> $message,
                ], $status ?: 500);
            }
        });

    })->create();
