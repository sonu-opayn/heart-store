<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserRegisterationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('sign-up', [UserRegisterationController::class, 'signUp']);
Route::post("/email-verify", [UserRegisterationController::class, 'emailVerify']);
Route::post("/resend-code", [UserRegisterationController::class, 'resendEmailVerifyCode']);
Route::post("/forget-password/send-code", [UserController::class, 'sendForgetPasswordCode']);
Route::post("/reset-possword", [UserController::class, 'resetPassword']);
Route::post('/login', [UserController::class, 'login']);
Route::get('/categories', [CategoryController::class, 'getListing']);
Route::get('/products', [ProductController::class, 'getListing']);
Route::group(['middleware' => 'auth:api'], function () {
    Route::post("/logout", [UserController::class, 'logout']);
    Route::post("/change-password", [UserController::class, 'changePassword']);
});