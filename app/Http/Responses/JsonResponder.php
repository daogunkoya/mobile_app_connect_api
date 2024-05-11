<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;

class JsonResponder
{
    public static function success($data = null, $message = null): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ], 200);
    }

    public static function error($message = 'An error occurred', $status = 400, $data = null): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    // You can add more methods for different response types as needed
}
