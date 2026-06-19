<?php

namespace App\Support;

use Illuminate\Http\JsonResponse;

class ApiError
{
    public static function response(string $message, int $status, array $errors = []): JsonResponse
    {
        $payload = ['message' => $message];

        if (!empty($errors)) {
            $payload['errors'] = $errors;
        }

        return response()->json($payload, $status);
    }
}
