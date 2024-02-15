<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Throwable;

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
    public function render($request, Throwable $exception){
        if ($exception instanceof MethodNotAllowedHttpException) {
            // Personaliza la respuesta JSON para el error de método no permitido
            return response()->json(['success'=>false,'message' => 'Method Not Allowed'], 405);
        }

         /* if ($exception instanceof AuthorizationException || !Auth::check()) {
            return response()->json([
                'success'=>false,
                'message' => 'Unauthorized'
            ],401);
        } */

    }


    public function register()
    {
        $this->renderable(function (AuthenticationException $e, $request) {
            return response()->json([
                'success'=>false,
                'message' => 'Unauthenticated token'
            ],401);
        });
    }
}
