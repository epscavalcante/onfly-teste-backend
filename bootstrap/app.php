<?php

use App\Exceptions\CannotAccessFlightDetailException;
use App\Exceptions\CannotApproveFlightException;
use App\Exceptions\CannotCancelFlightException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Response;
use Illuminate\Validation\UnauthorizedException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->report(function (UnauthorizedException $e) {
            abort(Response::HTTP_UNAUTHORIZED, $e->getMessage());
        });

        $exceptions->report(function (CannotApproveFlightException $e) {
            abort(Response::HTTP_FORBIDDEN, $e->getMessage());
        });

        $exceptions->report(function (CannotCancelFlightException $e) {
            abort(Response::HTTP_FORBIDDEN, $e->getMessage());
        });

        $exceptions->report(function (CannotAccessFlightDetailException $e) {
            abort(Response::HTTP_FORBIDDEN, $e->getMessage());
        });
    })->create();
