<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function (ModelNotFoundException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'Record not found.'
                ], 404);
            }
        });
    }

    // public function render($request, Throwable $e)
    // {
    //     if ($request->expectsJson()) {
    //         if ($e instanceof ModelNotFoundException) {
    //             return response()->json([
    //                 'message' => 'Record not found.',
    //             ], 404);
    //         }
    //     }

    //     return parent::render($request, $e);
    // }


    // public function render($request, Throwable $e)
    // {
    //     try {
    //         JWTAuth::parseToken()->authenticate();
    //     } catch (TokenExpiredException $e) {
    //         return response()->json(['status' => 'Token is Invalid'], 401);
    //     } catch (TokenInvalidException $e) {
    //         return response()->json(['status' => 'Token is Expired'], 401);
    //     } catch (JWTException $e) {
    //         return response()->json(['status' => 'Token not found'], 401);
    //     }
    // }
}
