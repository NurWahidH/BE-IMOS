<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AsetController;
use App\Http\Controllers\LokasiController;
use App\Http\Controllers\KategoriAsetController;

Route::get('/aset', [AsetController::class, 'index']);
Route::post('/aset', [AsetController::class, 'store']);

Route::get('/lokasi', [LokasiController::class, 'index']);
Route::post('/lokasi', [LokasiController::class, 'store']);

Route::resource('kategoriaset', KategoriAsetController::class);
