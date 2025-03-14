<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ParkingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Routes requiring authentication
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', fn(Request $request) => $request->user());

    // User routes
    Route::get('/parking/search', [ParkingController::class, 'search']);
    Route::post('/parking/reserve', [ParkingController::class, 'reserve']);
    Route::put('/reservations/{id}', [ParkingController::class, 'updateReservation']);
    Route::delete('/reservations/{id}', [ParkingController::class, 'cancelReservation']);
    Route::get('/reservations', [ParkingController::class, 'myReservations']);

    // Admin routes requiring 'role:admin'
    Route::middleware('role:admin')->group(function () {
        Route::post('/parkings', [ParkingController::class, 'store']);
        Route::put('/parkings/{id}', [ParkingController::class, 'update']);
        Route::delete('/parkings/{id}', [ParkingController::class, 'destroy']);
        Route::get('/parking/stats', [ParkingController::class, 'stats']);
    });
});