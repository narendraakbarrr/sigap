<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

use App\Http\Controllers\Web\ReportController;
use App\Http\Controllers\Web\DashboardController;

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Laporan
        Route::get('/reports',                    [ReportController::class, 'index'])
            ->name('reports.index');
        Route::get('/reports/{report}',           [ReportController::class, 'show'])
            ->name('reports.show');
        Route::put('/reports/{report}/status',    [ReportController::class, 'updateStatus'])
            ->name('reports.updateStatus');
        Route::delete('/reports/{report}',        [ReportController::class, 'destroy'])
            ->name('reports.destroy');
    });

require __DIR__ . '/auth.php';
