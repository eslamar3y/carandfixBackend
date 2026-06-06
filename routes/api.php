<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CarController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\LookupController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\StaticContentController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/guestLogin', [AuthController::class, 'guestLogin']);
Route::post('/forgetPassword', [AuthController::class, 'forgetPassword']);
Route::get('/latestVersion', [AuthController::class, 'latestVersion']);

Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])->name('verification.verify');
Route::get('/email/verify-redirect/{id}/{hash}', [AuthController::class, 'verifyEmailRedirect'])->name('verification.verify.redirect');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm']);
Route::post('/reset-password', [AuthController::class, 'resetPasswordWithToken']);

Route::get('/home', [HomeController::class, 'index']);
Route::get('/carTypes', [LookupController::class, 'carTypes']);
Route::get('/engineTypes', [LookupController::class, 'engineTypes']);
Route::get('/batteryVoltageTypes', [LookupController::class, 'batteryVoltageTypes']);
Route::get('/terms', [StaticContentController::class, 'terms']);
Route::get('/about', [StaticContentController::class, 'about']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/resetPassword', [AuthController::class, 'resetPassword']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/deleteAccount', [AuthController::class, 'deleteAccount']);
    Route::post('/email/resend', [AuthController::class, 'sendVerificationEmail']);

    Route::get('/profile', [ProfileController::class, 'show']);
    Route::post('/profile', [ProfileController::class, 'update']);
    Route::post('/updateLocale', [ProfileController::class, 'updateLocale']);
    Route::post('/reportIssue', [ProfileController::class, 'reportIssue']);

    Route::get('/cars', [CarController::class, 'index']);
    Route::post('/car', [CarController::class, 'store']);

    Route::post('/newOrder', [OrderController::class, 'store']);
    Route::get('/listOrders', [OrderController::class, 'index']);
    Route::post('/approveOrder', [OrderController::class, 'approve']);
    Route::post('/cancelOrder', [OrderController::class, 'cancel']);

    Route::get('/listNotifications', [NotificationController::class, 'index']);
    Route::post('/approveNotifications', [NotificationController::class, 'approve']);
    Route::post('/cancelNotifications', [NotificationController::class, 'cancel']);
});
