<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $exception)
    {
        dd($request);
        if ($request->expectsJson()) {
            // Handle API exceptions
            return $this->handleApiException($request, $exception);
        }

        // Handle web exceptions (default)
        return parent::render($request, $exception);
    }

    /**
     * Handle exceptions for API requests.
     */
    protected function handleApiException($request, Throwable $exception)
    {
        if ($exception instanceof AuthenticationException) {
            return response()->json([
                'message' => 'Unauthenticated',
                'errors' => $exception->getMessage(),
            ], 401);
        }

        if ($exception instanceof ValidationException) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $exception->validator->errors(),
            ], 422);
        }

        if ($exception instanceof NotFoundHttpException) {
            return response()->json([
                'message' => 'Not Found',
                'errors' => $exception->getMessage(),
            ], 404);
        }

        if ($exception instanceof ModelNotFoundException) {
            return response()->json([
                'message' => 'Object not found',
                'errors' => $exception->getMessage(),
            ], 404);
        }

        if ($exception instanceof QueryException) {
            return response()->json([
                'message' => 'Database query error',
                'errors' => $exception->getMessage(),
            ], 500);
        }

        return response()->json([
            'message' => 'An unexpected error occurred',
            'errors' => $exception->getMessage(),
        ], 500);
    }
}
