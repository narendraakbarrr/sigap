<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProfileController;

Route::prefix('v1')->group(function () {

    // Public
    Route::post('/login',    [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);

    // Protected
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me',      [AuthController::class, 'me']);

        // Reports
        Route::apiResource('reports', ReportController::class);
        Route::put('reports/{report}/status', [ReportController::class, 'updateStatus'])
            ->middleware('role:admin');

        // Categories (read-only untuk Flutter)
        Route::get('categories', [CategoryController::class, 'index']);

        Route::get('/announcements', [\App\Http\Controllers\Api\AnnouncementController::class, 'index']);
        
        Route::get('/profile',  [ProfileController::class, 'show']);
        Route::put('/profile',  [ProfileController::class, 'update']);
    });
});
