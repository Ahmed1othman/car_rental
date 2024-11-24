<?php

namespace App\Exceptions;

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

    public function render($request, Throwable $exception)
    {
        return $this->handleApiException($request, $exception);
    }

    protected function handleApiException($request, $exception)
    {
        if ($exception instanceof ValidationException) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $exception->validator->errors(),
            ], 422);
        }

        if ($exception instanceof NotFoundHttpException) {
            return response()->json([
                'message' => 'An error occurred',
                'errors' => $exception->getMessage(),
            ], 404);
        }

        if ($exception instanceof ModelNotFoundException ) {
            return response()->json([
                'message' => 'object not found',
                'errors' => $exception->getMessage(),
            ], 404);
        }

        if ($exception instanceof \Exception) {
            return response()->json([
                'message' => 'An error occurred',
                'errors' => $exception->getMessage(),
            ], 500);
        }
        return parent::render($request, $exception);
    }

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
