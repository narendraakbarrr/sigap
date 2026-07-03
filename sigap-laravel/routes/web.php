<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\DashboardController;

Route::get('/', fn() => redirect()->route('login'));

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
         ->name('dashboard');
});

// Grup admin — akan diisi di CP2 & CP3
Route::middleware(['auth', 'role:admin'])
     ->prefix('admin')
     ->name('admin.')
     ->group(function () {
         // CP2: reports, CP3: categories, users
     });

require __DIR__.'/auth.php';