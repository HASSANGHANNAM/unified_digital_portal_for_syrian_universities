<?php

use App\Models\User;
use App\Http\Controllers\Api\V1\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

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


Route::post('/login',[AuthController::class,'login']);



Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/refreshToken', [AuthController::class, 'refreshToken']);
    Route::post('/setup-account', [AuthController::class, 'setupAccount']);
    Route::post('/resend-code', [AuthController::class, 'resendCode']);
    Route::post('/verify-code', [AuthController::class, 'verifyCode']);
    Route::post('/upload-document', [AuthController::class, 'uploadDocument']);
    Route::post('/complete-profile', [AuthController::class, 'completeProfile']);
    Route::post('/submit', [AuthController::class, 'submit']);

});


Route::middleware(['auth:sanctum','CheckStatus'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

});
