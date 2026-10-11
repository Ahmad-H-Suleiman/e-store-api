<?php

use App\Exceptions\ProductUnavailableException;
use App\Exceptions\UnauthorizedActionException;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsSeller;
use App\Http\Middleware\seller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

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

        $exceptions->shouldRenderJsonWhen(function (Request $request, Throwable $e){
            return $request->is('api/*')||$request->expectsJson();
        });

        $exceptions->render(function (ModelNotFoundException $e, Request $request){
            if($request->is('api/*')){
                return response()->json(['message'=>'the requested resorce was not found'], 404);
            }
        });

        $exceptions->render(function(ProductUnavailableException $e){
            return response()->json(['message'=>$e->getMessage()], 409);
        });

        $exceptions->render(function(UnauthorizedActionException $e){
            return response()->json(['message'=>$e->getMessage()], 403);
        });

    })->create();
