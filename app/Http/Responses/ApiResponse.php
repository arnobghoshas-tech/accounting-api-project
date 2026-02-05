<?php

namespace App\Http\Responses;

class ApiResponse
{
    /**
     * Success response with data
     */
    public static function success($data = null, string $message = 'Request successful', int $statusCode = 200)
    {
        return response()->json([
            'success' => true,
            'code' => $statusCode,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }

    /**
     * Created response (201)
     */
    public static function created($data = null, string $message = 'Resource created successfully')
    {
        return response()->json([
            'success' => true,
            'code' => 201,
            'message' => $message,
            'data' => $data,
        ], 201);
    }

    /**
     * Error response
     */
    public static function error(string $message, int $statusCode = 500, $data = null)
    {
        return response()->json([
            'success' => false,
            'code' => $statusCode,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }

    /**
     * Not found response (404)
     */
    public static function notFound(string $message = 'Resource not found')
    {
        return response()->json([
            'success' => false,
            'code' => 404,
            'message' => $message,
        ], 404);
    }

    /**
     * Validation error response (422)
     */
    public static function unprocessable(string $message = 'Validation failed', $errors = null)
    {
        $response = [
            'success' => false,
            'code' => 422,
            'message' => $message,
        ];

        if ($errors) {
            $response['errors'] = $errors;
        }

        return response()->json($response, 422);
    }

    /**
     * Conflict response (409)
     */
    public static function conflict(string $message = 'Resource conflict')
    {
        return response()->json([
            'success' => false,
            'code' => 409,
            'message' => $message,
        ], 409);
    }

    /**
     * Unauthorized response (401)
     */
    public static function unauthorized(string $message = 'Unauthorized')
    {
        return response()->json([
            'success' => false,
            'code' => 401,
            'message' => $message,
        ], 401);
    }

    /**
     * Server error response (500)
     */
    public static function serverError(string $message = 'Internal server error')
    {
        return response()->json([
            'success' => false,
            'code' => 500,
            'message' => $message,
        ], 500);
    }
}
