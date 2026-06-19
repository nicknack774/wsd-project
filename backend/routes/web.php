<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RedirectController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'service' => 'wsd-backend',
        'timestamp' => now(),
    ]);
});

Route::get('/r/{code}', [RedirectController::class, 'redirect']);
