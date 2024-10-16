<?php

use App\Http\Controllers\AgentController;
use App\Http\Controllers\Api\NotificationController as ApiNotificationController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\BasicController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WishlistController;
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
Route::middleware('auth:sanctum')->get('subscription', [UserController::class, 'subscription']);
Route::apiResource('properties', PropertyController::class);
Route::middleware('auth:sanctum')->post('compare', [PropertyController::class, 'compare']);
Route::middleware('auth:sanctum')->apiResource('bookings', BookingController::class);
Route::middleware('auth:sanctum')->get('bookings/accept/{id}', [BookingController::class, 'accept']);
Route::middleware('auth:sanctum')->get('bookings/reject/{id}', [BookingController::class, 'reject']);
Route::middleware('auth:sanctum')->get('bookings/cancelled/{id}', [BookingController::class, 'cancelled']);
Route::middleware('auth:sanctum')->apiResource('wishlist', WishlistController::class);
Route::get('basic/properties', [PropertyController::class, 'properties']);
Route::post('search', [PropertyController::class, 'search']);
Route::apiResource('agents', AgentController::class);
Route::middleware('auth:sanctum')->apiResource('review', ReviewController::class);
Route::get('generals', [BasicController::class, 'index']);
Route::middleware('auth:sanctum')->post('sub-payment', [BasicController::class, 'subPayment']);

Route::middleware('auth:api')->group(function() {
    // Get all notifications
    Route::get('/notifications', [ApiNotificationController::class, 'index']);

    // Mark a notification as read
    Route::post('/notifications/{id}/read', [ApiNotificationController::class, 'markAsRead']);

    // Mark all notifications as read
    Route::post('/notifications/read-all', [ApiNotificationController::class, 'markAllAsRead']);
});

Route::post('/verify-email', [VerificationController::class, 'verifyEmail']);
Route::post('/resend-code', [VerificationController::class, 'resendEmail']);










