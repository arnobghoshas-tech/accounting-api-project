<?php

namespace App\Services;

/**
 * ServiceResponse class for standardized service-level responses
 * 
 * This class provides a standardized way for services to return
 * success/error responses that can be easily handled by controllers.
 */
class ServiceResponse
{
    private bool $success;
    private $data;
    private string $message;
    private int $statusCode;
    private $errors;

    public function __construct(bool $success, $data = null, string $message = '', int $statusCode = 200, $errors = null)
    {
        $this->success = $success;
        $this->data = $data;
        $this->message = $message;
        $this->statusCode = $statusCode;
        $this->errors = $errors;
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Create a success response
     */
    public static function success($data = null, string $message = 'Operation successful', int $statusCode = 200): self
    {
        return new self(true, $data, $message, $statusCode);
    }

    /**
     * Create an error response
     */
    public static function error(string $message, int $statusCode = 500, $data = null, $errors = null): self
    {
        return new self(false, $data, $message, $statusCode, $errors);
    }

    /**
     * Create a validation error response
     */
    public static function validationError(array $errors, string $message = 'Validation failed'): self
    {
        return new self(false, null, $message, 422, $errors);
    }

    /**
     * Create a not found error response
     */
    public static function notFound(string $message = 'Resource not found'): self
    {
        return new self(false, null, $message, 404);
    }

    /**
     * Create a conflict error response
     */
    public static function conflict(string $message = 'Resource conflict'): self
    {
        return new self(false, null, $message, 409);
    }
}
