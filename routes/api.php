<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\IzinController;
use App\Http\Controllers\Api\CutiController;
use App\Http\Controllers\Api\AbsensiController;
use App\Http\Controllers\Api\PenggajianController;

/*
|--------------------------------------------------------------------------
| API Routes - HRNexa ERP Mobile
|--------------------------------------------------------------------------
*/

// ===== AUTH (public) =====
Route::post('/login', [AuthApiController::class, 'login']);

// ===== AUTHENTICATED ROUTES =====
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout', [AuthApiController::class, 'logout']);
    Route::get('/user', [AuthApiController::class, 'me']);

    // Izin
    Route::get('/izins', [IzinController::class, 'index']);
    Route::get('/izins/{id}', [IzinController::class, 'show']);
    Route::post('/izins', [IzinController::class, 'store']);
    Route::put('/izins/{id}', [IzinController::class, 'update']);
    Route::patch('/izins/{id}/approve', [IzinController::class, 'approve']);

    // Cuti
    Route::get('/cutis', [CutiController::class, 'index']);
    Route::get('/cutis/{id}', [CutiController::class, 'show']);
    Route::post('/cutis', [CutiController::class, 'store']);
    Route::put('/cutis/{id}', [CutiController::class, 'update']);
    Route::patch('/cutis/{id}/approve', [CutiController::class, 'approve']);

    // Absensi
    Route::get('/absensis', [AbsensiController::class, 'index']);
    Route::get('/absensis/{id}', [AbsensiController::class, 'show']);
    Route::post('/absensis', [AbsensiController::class, 'store']);
    Route::put('/absensis/{id}', [AbsensiController::class, 'update']);

    // Penggajian
    Route::get('/penggajians', [PenggajianController::class, 'index']);
    Route::get('/penggajians/{id}', [PenggajianController::class, 'show']);
    Route::post('/penggajians', [PenggajianController::class, 'store']);
    Route::put('/penggajians/{id}', [PenggajianController::class, 'update']);
});
