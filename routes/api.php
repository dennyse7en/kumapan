<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CreditApplicationApiController;
use App\Http\Controllers\Api\TrackingApiController;
use App\Http\Controllers\Api\Admin\StatusUpdateController; 

// Rute Publik (tidak perlu login)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/track', [TrackingApiController::class, 'track']);

// Rute yang Dilindungi (perlu login/token)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/credit-applications', [CreditApplicationApiController::class, 'index']);
    Route::post('/credit-applications', [CreditApplicationApiController::class, 'store']);
    Route::get('/credit-applications/{id}', [CreditApplicationApiController::class, 'show']);

    // Kita akan menambahkan rute pengajuan kredit di sini nanti
});

Route::middleware(['auth:sanctum', 'role:Super Admin|verifikator|operator|approver'])
    ->prefix('admin') // Kita beri awalan /admin
    ->name('admin.')
    ->group(function () {
        
        // 2. Tambahkan rute ini
        Route::patch('/credit-applications/{application}/status', [StatusUpdateController::class, 'updateStatus'])
            ->name('credit-applications.update-status');
});