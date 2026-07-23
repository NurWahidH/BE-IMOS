<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\LokasiController;

// ----------------------------------------------------
// 1. Rute Publik (Bisa diakses tanpa login)
// ----------------------------------------------------
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::get('/assets/scan/{qr_code}', [AssetController::class, 'scan']); // Public User scan QR


// ----------------------------------------------------
// 2. Rute Wajib Login
// ----------------------------------------------------
Route::middleware('auth:api')->group(function () { // Pakai auth:api jika JWT, atau auth:sanctum

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::get('/units', [LokasiController::class, 'index']);

    // --- GRUP MASTER ADMIN ---
    Route::middleware('role:master_admin')->group(function () {
        Route::get('/users', [UserController::class, 'index']);
        Route::post('/users', [UserController::class, 'store']);
        Route::patch('/users/{id}/deactivate', [UserController::class, 'deactivate']);
        Route::put('/users/{id}', [UserController::class, 'update']);
        Route::delete('/users/{id}', [UserController::class, 'destroy']);
    });

    // --- GRUP ADMIN ---
    Route::middleware('role:admin')->group(function () {
        Route::post('/assets', [AssetController::class, 'store']);
        Route::post('/assets/upload', [AssetController::class, 'upload']);
        Route::put('/assets/{id}', [AssetController::class, 'update']);

    });

    // --- GRUP USER & PUBLIC (Yang sudah login) ---
    Route::middleware('role:user,public')->group(function () {
        Route::post('/mutations', [MutationController::class, 'store']); // Pengajuan mutasi
        Route::post('/complaints', [ComplaintController::class, 'store']); // Pengajuan laporan
    });

});
