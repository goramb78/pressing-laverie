<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\StatsController;
use App\Http\Controllers\Api\TicketController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/services', [ServiceController::class, 'index']);
Route::get('/services/{service}', [ServiceController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/tickets', [TicketController::class, 'index']);
    Route::get('/tickets/{ticket}', [TicketController::class, 'show']);
    Route::post('/tickets', [TicketController::class, 'store']);

    Route::middleware('role:gestionnaire')->group(function () {
        Route::post('/services', [ServiceController::class, 'store']);
        Route::put('/services/{service}', [ServiceController::class, 'update']);
        Route::delete('/services/{service}', [ServiceController::class, 'destroy']);

        Route::patch('/tickets/{ticket}/status', [TicketController::class, 'updateStatus']);
        Route::delete('/tickets/{ticket}', [TicketController::class, 'destroy']);

        Route::post('/tickets/{ticket}/payment', [PaymentController::class, 'store']);

        Route::get('/stats/daily', [StatsController::class, 'daily']);
        Route::get('/stats/tickets-per-month', [StatsController::class, 'ticketsPerMonth']);
        Route::get('/stats/revenue-per-service', [StatsController::class, 'revenuePerService']);
    });
});