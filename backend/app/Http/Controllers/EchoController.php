<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class EchoController extends Controller
{
    public function echo(Request $request): JsonResponse
    {
        $message = $request->input('message', 'Hello from EchoController');

        return response()->json([
            'message' => $message,
            'method' => $request->method(),
            'timestamp' => now(),
        ]);
    }
}
