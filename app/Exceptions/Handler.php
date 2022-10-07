<?php

namespace App\Exceptions;

use App\Services\JsonAPIResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
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
        /** checks for not found error */
        $this->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->is('api/*')) {
                return JsonAPIResponse::sendErrorResponse("Route or Record not Found", 404);
            }
        });

        /** checks unauthenticated error */
        $this->renderable(function (AuthenticationException $e, $request) {
            if ($request->is('api/*')) {
                return JsonAPIResponse::sendErrorResponse("Sorry! Your authorization token has expired, try logging in again", 404);
            }
        });
    }
}
