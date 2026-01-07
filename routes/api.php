<?php
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TaskController;

Route::post('/login',[AuthController::class,'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout',[AuthController::class,'logout']);
    Route::apiResource('tasks',TaskController::class);
    Route::patch('/tasks/{task}/status', [TaskController::class, 'updateStatus']);
});