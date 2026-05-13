<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ProfileController;
use App\Http\Controllers\Api\V1\NotificationController;
use Illuminate\Http\Request;
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
    Route::post('/resend-code', [AuthController::class, 'resendCode']);
    Route::post('/verify-code', [AuthController::class, 'verifyCode']);
    Route::post('/setup-account', [ProfileController::class, 'setupAccount']);
    Route::post('/upload-document', [ProfileController::class, 'uploadDocument']);
    Route::post('/complete-profile', [ProfileController::class, 'completeProfile']);
    Route::post('/submit', [ProfileController::class, 'submit']);
});


Route::middleware(['auth:sanctum', 'CheckStatus'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// 
// {
//     "event": "pusher:connection_established",
//     "data": "{\"socket_id\":\"753189335.31768004\",\"activity_timeout\":30}"
// }