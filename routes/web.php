<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MonitoringController;

Route::get('/login', [LoginController::class, 'showLoginForm'])
    ->name('login');

Route::post('/login', [LoginController::class, 'login']);

Route::get('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::get('/', function () {
    return view('login');
});

Route::get('/videotron', [DashboardController::class, 'videotron'])
        ->name('videotron');   

Route::middleware(['auth'])->group(function () {

    // Dashboard → semua role (admin, site, office)
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');    

    Route::get('/units', [UnitController::class, 'index'])->name('units.index');
    Route::post('/units', [UnitController::class, 'store'])->name('units.store');
    Route::get('/units/{unit}', [UnitController::class, 'show'])->name('units.show');
    Route::put('/units/{unit}', [UnitController::class, 'update'])->name('units.update');
    Route::delete('/units/{unit}', [UnitController::class, 'destroy'])->name('units.destroy');

    Route::get('/monitoring', [MonitoringController::class, 'index'])
        ->name('monitoring.index');

    Route::post('/monitoring/store', [MonitoringController::class, 'store'])
        ->name('monitoring.service.store');
    Route::get('/monitoring/service/{unit}/edit', [MonitoringController::class, 'edit']);
    Route::put('/monitoring/service/{service}', [MonitoringController::class, 'update']);
    Route::post('/monitoring/service/{service}/handover', [MonitoringController::class, 'handover']);
    Route::post('/monitoring/service/{service}/done', [MonitoringController::class, 'done']);

    Route::get('/monitoring/service/{service}/json', [MonitoringController::class, 'showJson']);

    Route::get('/monitoring/handover-users', [MonitoringController::class, 'handoverUsers']);
    Route::post(
        '/monitoring/service/{service}/handover',
        [MonitoringController::class, 'handover']
    )->name('monitoring.service.handover');
    Route::post(
        '/monitoring/service/{service}/end-job',
        [MonitoringController::class, 'endJob']
    )->name('monitoring.service.endJob');

    
    Route::get('/dashboard/download', [DashboardController::class, 'download'])
        ->name('dashboard.download');




    // Admin only
    Route::middleware('role:admin')->group(function () {

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    });



    // Site only
    Route::middleware('role:site')->group(function () {
        // route site
    });

    // Office only
    Route::middleware('role:office')->group(function () {
        // route office
    });
});