<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\PembeliController;
use App\Http\Controllers\ProdukController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

/*
|--------------------------------------------------------------------------
| Auth Pembeli
|--------------------------------------------------------------------------
*/
Route::post('/pembeli/register', [AuthController::class, 'register']);
Route::post('/pembeli/login', [AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| Pembeli (Public & Protected)
|--------------------------------------------------------------------------
*/
Route::get('/pembeli', [PembeliController::class, 'index']);
Route::get('/pembeli/{id}', [PembeliController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/pembeli', [PembeliController::class, 'store']);
    Route::patch('/pembeli/{id}', [PembeliController::class, 'update']);
    Route::delete('/pembeli/{id}', [PembeliController::class, 'destroy']);
});

/*
|--------------------------------------------------------------------------
| Produk (Public & Protected)
|--------------------------------------------------------------------------
*/
Route::get('/produk', [ProdukController::class, 'index']);
Route::get('/produk/{id}', [ProdukController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/produk', [ProdukController::class, 'store']);
    Route::patch('/produk/{id}', [ProdukController::class, 'update']);
    Route::delete('/produk/{id}', [ProdukController::class, 'destroy']);
});
