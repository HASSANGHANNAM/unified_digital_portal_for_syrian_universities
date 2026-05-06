<?php

use Illuminate\Support\Facades\Route;
use App\Events\TestWebSocket;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/session-test', function () {
    session(['user_id' => 123]);
    return session('user_id');
});

Route::get('/test-broadcast', function () {
    event(new TestWebSocket('مرحباً من Reverb!'));
    return 'تم إرسال الحدث.';
});
