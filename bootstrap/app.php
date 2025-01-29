<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Session\Middleware\StartSession; // Import StartSession
use Illuminate\Session\TokenMismatchException; // Import TokenMismatchException

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Define middleware aliases
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'redirect.unauthenticated' => App\Http\Middleware\RedirectIfUnauthenticated::class,
            'checkFirstLogin' => App\Http\Middleware\CheckFirstLogin::class,
        ]);

        // Define middleware priority
        $middleware->priority([
            StartSession::class, // Ensure StartSession is at the top
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Contracts\Auth\Middleware\AuthenticatesRequests::class,
            // Add other middleware here as needed
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Handle TokenMismatchException
        $exceptions->renderable(function (TokenMismatchException $e, $request) {

            return redirect()->route('login')->with('error', 'Your session has expired. Please log in again.');
        });
    })
    ->create();
