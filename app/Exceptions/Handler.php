<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use App\Http\Resources\ValidationErrorResource;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render( $request, Throwable $exception): \Illuminate\Http\Response|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\Response
    {
        if ($exception instanceof RateNotSetException) {
            // Custom rendering logic for your custom exception
            return response()->json(['error' => $exception->getMessage()], 500);
        }

        if ($exception instanceof CommissionNotSetException) {
            // Custom rendering logic for your custom exception
            return response()->json(['error' => $exception->getMessage()], 500);
        }

        return parent::render($request, $exception);
    }


     /**
     * Convert a validation exception into a JSON response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Validation\ValidationException  $exception
     * @return \Illuminate\Http\JsonResponse
     */
    protected function invalidJson($request, ValidationException $exception)
    {
        return (new ValidationErrorResource($exception->errors()))
                ->response()
                ->setStatusCode(422);
    }
}
