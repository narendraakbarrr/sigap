<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Web\CategoryController;
use App\Http\Controllers\Web\ReportController;
use App\Http\Controllers\Web\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\UserController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');


    Route::middleware(['role:admin'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {

            Route::get('/dashboard', [DashboardController::class, 'index'])
                ->name('dashboard');

            Route::resource('categories', CategoryController::class)
                ->except(['show']);

            Route::get('/reports', [ReportController::class, 'index'])
                ->name('reports.index');

            Route::get('/reports/trash', [ReportController::class, 'trash'])
                ->name('reports.trash');

            Route::get('/reports/{report}', [ReportController::class, 'show'])
                ->name('reports.show');

            Route::put('/reports/{report}/status', [ReportController::class, 'updateStatus'])
                ->name('reports.updateStatus');

            Route::delete('/reports/{report}', [ReportController::class, 'destroy'])
                ->name('reports.destroy');

            Route::put('/reports/{id}/restore', [ReportController::class, 'restore'])
                ->name('reports.restore');

            Route::delete('/reports/{id}/force', [ReportController::class, 'forceDelete'])
                ->name('reports.forceDelete');
            // --- end reports ---

            Route::get('/users', [UserController::class, 'index'])
                ->name('users.index');

            Route::get('/users/{user}', [UserController::class, 'show'])
                ->name('users.show');

            Route::put('/users/{user}/role', [UserController::class, 'updateRole'])
                ->name('users.updateRole');

            Route::delete('/users/{user}', [UserController::class, 'destroy'])
                ->name('users.destroy');
        });

    Route::middleware(['role:user'])
        ->prefix('user')
        ->name('user.')
        ->group(function () {
            Route::get('/reports', [\App\Http\Controllers\Web\ReportController::class, 'userIndex'])
                ->name('reports.index');
        });
});

require __DIR__ . '/auth.php';
