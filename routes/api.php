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





// Gửi lại link xác minh
// Route::post('/email/resend', function (Request $request) {
//     if ($request->user()->hasVerifiedEmail()) {
//         return response()->json(['message' => 'Email đã xác minh']);
//     }

//     $request->user()->sendEmailVerificationNotification();

//     return response()->json(['message' => 'Link xác minh đã được gửi lại']);
// })->middleware(['auth:api']);

Route::post('/email/resend', function (Request $request) {
    $user = $request->user();

    if (!$user) {
        return response()->json(['message' => 'Người dùng chưa đăng nhập'], 401);
    }

    if ($user->hasVerifiedEmail()) {
        return response()->json(['message' => 'Email đã xác minh']);
    }

    $user->sendEmailVerificationNotification();

    return response()->json(['message' => 'Link xác minh đã được gửi lại']);
})->middleware(['auth:api']);




Route::post('/upload', [CloudinaryUploadController::class, 'upload'])->name('api.upload');
