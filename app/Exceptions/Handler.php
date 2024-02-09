<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;
use JustSteveKing\StatusCode\Http;

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
        $this->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status_code' => Http::NOT_FOUND(),
                    'message' => 'Record not found.'
                ], Http::NOT_FOUND());
            }
        });

        $this->renderable(function (ValidationException  $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status_code' => Http::BAD_REQUEST(),
                    'message' => $e->getMessage(),
                    'errors' => $e->errors()
                ], Http::BAD_REQUEST());
            }
        });
    }
}
