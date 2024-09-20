<?php

use App\Http\Controllers\AgentController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\BasicController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WishlistController;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::post('/register', [RegistrationController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);
route::middleware('auth:sanctum')->post('change-password', [UserController::class, 'changePassword']);
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail']);
Route::post('/reset-password', [ResetPasswordController::class, 'reset']);
Route::middleware('auth:sanctum')->get('/user', [UserController::class, 'show']);
Route::middleware('auth:sanctum')->put('/user', [UserController::class, 'update']);
Route::middleware('auth:sanctum')->apiResource('properties', PropertyController::class);
Route::middleware('auth:sanctum')->post('compare', [PropertyController::class, 'compare']);
Route::middleware('auth:sanctum')->apiResource('bookings', BookingController::class);
Route::middleware('auth:sanctum')->apiResource('wishlist', WishlistController::class);
Route::get('basic/properties', [PropertyController::class, 'properties']);
Route::post('search', [PropertyController::class, 'search']);









