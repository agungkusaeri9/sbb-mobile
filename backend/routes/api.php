<?php

use App\Http\Controllers\API\V1\Auth\LoginController;
use App\Http\Controllers\API\V1\Auth\RegisterController;
use App\Http\Controllers\API\V1\Auth\VerifyController;
use App\Http\Controllers\API\V1\ChatController;
use App\Http\Controllers\API\V1\ProductController;
use App\Http\Controllers\API\V1\ProfileController;
use App\Http\Controllers\API\V1\UserController;
use App\Http\Middleware\authJwt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::middleware(['api'])->group(function () {
    Route::prefix('v1')->group(function () {
        Route::prefix('auth')->group(function () {
            Route::post('login', LoginController::class);
            Route::post('register', RegisterController::class);
            Route::post('/validate-token', function (Request $request) {
                $token = $request->bearerToken();
                // Validasi token
                $user = Auth::guard('api')->user();
                if (!$user || $user->is_deleted) {
                    return response()->json(['message' => 'Invalid token or user not found'], 401);
                }
                return response()->json(['message' => 'Token is valid']);
            });
        });

        Route::middleware(authJwt::class)->group(function () {
            Route::delete('users/deletes', [UserController::class, 'delete_array']);
            Route::apiResource('users', UserController::class);
            Route::get('products/all', [ProductController::class, 'all']);
            Route::apiResource('products', ProductController::class);
            Route::get('chat/markAsRead', [ChatController::class, 'markAsRead']);
            Route::get('chat/allByUserId', [ChatController::class, 'allByUserId']);
            Route::apiResource('chat', ChatController::class);
            Route::get('profile', [ProfileController::class, 'get']);
            Route::post('profile', [ProfileController::class, 'update']);
        });
    });
});
