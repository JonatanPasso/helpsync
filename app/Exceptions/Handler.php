<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param \Exception $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Exception $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function render($request, Exception $exception)
    {


        if ($request->isJson()) {

            $msg = $exception->getMessage();
            $code = 500;

            if ($exception instanceof HttpException) {
                $msg = 'Page not found.';
                $code = 404;
            }

            if ($exception instanceof ValidationException) {

                return response($exception->validator->errors(), 400);
            }


            return response()->json([
                'status' => $code,
                'erro' => $msg ? $msg : 'Erro desconhecido.',
                'trace' => env('APP_DEBUG', false) ? explode("\n", $exception->getTraceAsString()) : null
            ], $code);
        }
        return parent::render($request, $exception);
    }
}
