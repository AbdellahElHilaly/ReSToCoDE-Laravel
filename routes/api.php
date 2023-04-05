<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\GameController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\API\PermissionController;


Route::get('/test', function () {
    return response()->json(['message' => 'API is working', 'status' => 'Connected']);
});

Route::apiResource('categories', CategoryController::class);
Route::apiResource('games', GameController::class);
Route::apiResource('permissions', PermissionController::class);


Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('edite', [AuthController::class, 'editProfile']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('refresh', [AuthController::class, 'refresh']);
    Route::get('profile', [AuthController::class, 'getProfile']);
    Route::get('delete', [AuthController::class, 'deleteProfile']);
    Route::get('activate', [AuthController::class, 'activateAccount']);
    Route::post('forgotPassword', [AuthController::class, 'forgotPassword']);
    Route::get('ressetpassword', [AuthController::class, 'ressetPassword']);
    Route::post('resendcode', [AuthController::class, 'resendActivationMail']);
    Route::get('trust', [AuthController::class, 'trushDevice']);

});

