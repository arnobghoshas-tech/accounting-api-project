<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class Handler extends ExceptionHandler
{
    public function register(): void
    {
    }

    protected function invalidJson($request, ValidationException $exception)
    {
        return response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $exception->errors(),
        ], $exception->status);
    }

    public function render($request, Throwable $e)
    {
        if ($e instanceof ValidationException) {
            return $this->invalidJson($request, $e);
        }

        if ($e instanceof AuthenticationException) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated',
            ], 401);
        }

        if ($request->is('api/*')) {
            $status = 500;
            $message = 'Server error';

            if ($e instanceof HttpExceptionInterface) {
                $status = $e->getStatusCode();
                $message = $e->getMessage() ?: $message;
            }

            return response()->json([
                'success' => false,
                'message' => $message,
            ], $status);
        }

        return parent::render($request, $e);
    }
}
