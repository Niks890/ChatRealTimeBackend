<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CloudinaryUploadController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group([
    'prefix' => 'auth'

], function ($router) {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login',  [AuthController::class, 'login']);
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('reset-password', [AuthController::class, 'resetPassword']);
});

Route::middleware('auth:api')->group(function () {
    Route::post('me', [AuthController::class, 'me']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
});




Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill(); // Đánh dấu đã xác minh
    // Chuyển hướng người dùng đến frontend sau khi xác minh
    return redirect(config('app.frontend_url') . '/verify-account');
})->middleware(['signed'])->name('verification.verify');

// Gửi lại link xác minh
Route::post('/email/resend', function (Request $request) {
    if ($request->user()->hasVerifiedEmail()) {
        return response()->json(['message' => 'Email đã xác minh']);
    }

    $request->user()->sendEmailVerificationNotification();

    return response()->json(['message' => 'Link xác minh đã được gửi lại']);
})->middleware(['auth:api']);



Route::post('/upload', [CloudinaryUploadController::class, 'upload'])->name('api.upload');
