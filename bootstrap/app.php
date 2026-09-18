<?php

use App\Exceptions\ProductUnavailableException;
use App\Exceptions\UnauthorizedActionException;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsSeller;
use App\Http\Middleware\seller;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'seller'=>IsSeller::class,
            'admin'=>IsAdmin::class,
        
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function(ProductUnavailableException $e){
            return response()->json(['message'=>$e->getMessage()], 409);
        });

        $exceptions->render(function(UnauthorizedActionException $e){
            return response()->json(['message'=>$e->getMessage()], 403);
        });

    })->create();
