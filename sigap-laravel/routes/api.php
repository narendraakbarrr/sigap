<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProfileController;

/*
|--------------------------------------------------------------------------
| API Routes (v1)
|--------------------------------------------------------------------------
| Penjelasan singkat:
| - Semua route berada di prefix `/api/v1`.
| - Ada pembagian Public (registrasi/login) dan Protected (memerlukan Sanctum).
| - Beberapa route dilindungi role-specific middleware (mis. update status oleh admin).
| - Endpoint ini dipakai oleh frontend mobile/web (Flutter) untuk operasi laporan,
|   autentikasi, kategori, profile, dan pengumuman.
*/

Route::prefix('v1')->group(function () {

    // Public
    Route::post('/login',    [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);

    // Protected
    Route::middleware('auth:sanctum')->group(function () {
        // Token-based routes: logout/me
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me',      [AuthController::class, 'me']);

        // Reports: full CRUD via apiResource; status update dibatasi untuk admin
        Route::apiResource('reports', ReportController::class);
        Route::put('reports/{report}/status', [ReportController::class, 'updateStatus'])
            ->middleware('role:admin');

        // Categories (read-only untuk frontend)
        Route::get('categories', [CategoryController::class, 'index']);

        // Announcements dan profile
        Route::get('/announcements', [\App\Http\Controllers\Api\AnnouncementController::class, 'index']);
        Route::get('/profile',  [ProfileController::class, 'show']);
        Route::put('/profile',  [ProfileController::class, 'update']);
    });
});
