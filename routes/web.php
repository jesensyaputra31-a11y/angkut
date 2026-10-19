<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OfficerController;
use App\Http\Controllers\HomeController;

// Route Publik & Beranda
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/lapor', [ReportController::class, 'create'])->name('reports.create');
Route::post('/laporan', [ReportController::class, 'store'])->name('reports.store');

// Route Pelacakan Laporan
Route::get('/lacak', [HomeController::class, 'trackForm'])->name('track.form');
Route::post('/lacak', [HomeController::class, 'track'])->name('track.process');
Route::get('/lacak/{code}', [HomeController::class, 'trackResult'])->name('track.result');

// Route Autentikasi
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Route Khusus Pengguna Login (Masyarakat)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [ReportController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Route Khusus Admin (DLH)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/reports', [AdminController::class, 'index'])->name('reports.index');
    Route::get('/reports/{report}', [AdminController::class, 'show'])->name('reports.show');
    Route::post('/reports/{report}/verify', [AdminController::class, 'verify'])->name('reports.verify');
    Route::post('/reports/{report}/assign', [AdminController::class, 'assign'])->name('reports.assign');
    Route::post('/reports/{report}/finalize', [AdminController::class, 'finalize'])->name('reports.finalize');
});

// Route Khusus Petugas Lapangan
Route::middleware(['auth', 'role:officer'])->prefix('officer')->name('officer.')->group(function () {
    Route::get('/dashboard', [OfficerController::class, 'dashboard'])->name('dashboard');
    Route::get('/reports/{report}', [OfficerController::class, 'show'])->name('reports.show');
    Route::post('/reports/{report}/complete', [OfficerController::class, 'complete'])->name('reports.complete');
});