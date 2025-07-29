<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\CreditApplicationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TrackingController;

Route::get('/', function () {
    return view('welcome');
});

// Rute untuk menampilkan form pelacakan
Route::get('/track', [TrackingController::class, 'showForm'])->name('track.form');

// Rute untuk menampilkan hasil status
Route::get('/track/status', [TrackingController::class, 'showStatus'])->name('track.status');


// Rute untuk proses otentikasi dengan Google
Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('google.callback');


// Grup rute yang hanya bisa diakses setelah user login dan verifikasi email
Route::middleware(['auth', 'verified'])->group(function () {

    // Rute dashboard bawaan dari Breeze
    Route::get('/dashboard', [CreditApplicationController::class, 'index'])->name('create.dashboard');

    // Rute untuk fitur pengajuan kredit oleh 'pengguna'
    Route::get('/kredit/dashboard', [CreditApplicationController::class, 'index'])->name('credit.dashboard');
    Route::get('/kredit/create', [CreditApplicationController::class, 'create'])->name('credit.create');
    Route::post('/kredit/store', [CreditApplicationController::class, 'store'])->name('credit.store');

    // Rute untuk manajemen profil user
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
