<?php

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\ApiKeyMiddleware;
use App\Http\Middleware\SecurityHeaders;
use App\Http\Middleware\VoterMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->append(SecurityHeaders::class);

        $middleware->alias([
            'admin' => AdminMiddleware::class,
            'api.key' => ApiKeyMiddleware::class,
            'voter' => VoterMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );

        $exceptions->render(function (TokenMismatchException $e, Request $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['message' => 'Session expired. Please refresh the page.'], 419);
            }

            return redirect()->route('login')->with('error', 'Your session has expired. Please log in again.');
        });
    })->create();
