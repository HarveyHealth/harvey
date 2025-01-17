<?php

namespace App\Exceptions;

use Crell\ApiProblem\ApiProblem;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Exception;
use ResponseCode;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        \App\Exceptions\ServiceUnavailableException::class,
        \App\Exceptions\StrictValidatorException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
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
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof ModelNotFoundException && request()->expectsJson()) {
            $problem = new ApiProblem('Not Found.');
            $problem->setDetail($exception->getMessage());
            return response()->apiproblem($problem->asArray(), 404);
        } elseif ($exception instanceof StrictValidatorException) {
            $problem = new ApiProblem('Bad Request.');
            $problem->setDetail($exception->getMessage());
            return response()->apiproblem($problem->asArray(), ResponseCode::HTTP_BAD_REQUEST);
        } elseif ($exception instanceof ServiceUnavailableException) {
            $problem = new ApiProblem('Service Unavailable.');
            $problem->setDetail($exception->getMessage());
            return response()->apiproblem($problem->asArray(), ResponseCode::HTTP_SERVICE_UNAVAILABLE);
        }

        return parent::render($request, $exception);
    }
}
