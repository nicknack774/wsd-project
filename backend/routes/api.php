<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EchoController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\ShortLinkController;
use App\Http\Controllers\Api\RestaurantController;

Route::get('/echo', [EchoController::class, 'echo']);
Route::post('/echo', [EchoController::class, 'echo']);

Route::prefix('77963/v1')->group(function () {
    Route::get('/tasks', [TaskController::class, 'index']);
    Route::post('/tasks', [TaskController::class, 'store']);
    Route::get('/tasks/{id}', [TaskController::class, 'show']);
    Route::put('/tasks/{id}', [TaskController::class, 'update']);
    Route::patch('/tasks/{id}', [TaskController::class, 'update']);
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy']);

    Route::get('/short-links', [ShortLinkController::class, 'index']);
    Route::post('/short-links', [ShortLinkController::class, 'store']);
    Route::get('/short-links/{id}', [ShortLinkController::class, 'show']);

    Route::get('/restaurants/nearby', [RestaurantController::class, 'nearby']);
    Route::apiResource('restaurants', RestaurantController::class)->only(['index', 'store', 'show']);
});
