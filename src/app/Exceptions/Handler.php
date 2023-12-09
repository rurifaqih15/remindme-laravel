<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
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

    public function render($request, Throwable $exception)
    {
        if ($this->isHttpException($exception)) {
            $statusCode = $exception->getStatusCode();

            $customError = [
                'ok' => false,
                'err' => $this->getErrorKey($statusCode),
                'msg' => $this->getErrorMessage($statusCode),
            ];

            return response()->json($customError, $statusCode);
        }

        return parent::render($request, $exception);
    }

    private function getErrorKey($statusCode)
    {
        // Definisikan pemetaan kunci error berdasarkan status code
        $errorKeys = [
            400 => 'ERR_BAD_REQUEST',
            401 => 'ERR_INVALID_ACCESS_TOKEN',
            403 => 'ERR_FORBIDDEN_ACCESS',
            404 => 'ERR_NOT_FOUND',
            500 => 'ERR_INTERNAL_ERROR',
        ];

        return $errorKeys[$statusCode] ?? 'ERR_UNKNOWN_ERROR';
    }

    // Fungsi untuk mendapatkan pesan error berdasarkan status code
    private function getErrorMessage($statusCode)
    {
        // Definisikan pemetaan pesan error berdasarkan status code
        $errorMessages = [
            400 => 'Invalid value of `type`',
            401 => 'Invalid access token',
            403 => 'User doesn\'t have enough authorization',
            404 => 'Resource is not found',
            500 => 'Unable to connect into database',
        ];

        return $errorMessages[$statusCode] ?? 'Unknown error occurred';
    }


}
