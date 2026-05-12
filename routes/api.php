<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\NotificationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('V1')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('notifications', [NotificationController::class, 'index']);
        Route::get('notifications/unread-count', [NotificationController::class, 'unreadCount']);
        Route::patch('notifications/{id}/read', [NotificationController::class, 'markAsRead']);
        Route::post('notifications/read-all', [NotificationController::class, 'markAllAsRead']);
    });
});



Route::post('/login', [AuthController::class, 'login']);



Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/refreshToken', [AuthController::class, 'refreshToken']);
    Route::post('/setup-account', [AuthController::class, 'setupAccount']);
    Route::post('/resend-code', [AuthController::class, 'resendCode']);
    Route::post('/verify-code', [AuthController::class, 'verifyCode']);
    Route::post('/upload-document', [AuthController::class, 'uploadDocument']);
    Route::post('/complete-profile', [AuthController::class, 'completeProfile']);
    Route::post('/submit', [AuthController::class, 'submit']);
});


Route::middleware(['auth:sanctum', 'CheckStatus'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});
