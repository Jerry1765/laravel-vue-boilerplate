<?php

use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\TwoFactorAuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::prefix('auth')->middleware(['throttle:api'])->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/login/2fa', [AuthController::class, 'loginWithTwoFactor']);
    Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verify'])
        ->name('verification.verify');
    Route::post('/email/resend', [AuthController::class, 'resend'])
        ->middleware(['throttle:5,60']); // 5 attempts per hour
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::post('/reset-password', [ForgotPasswordController::class, 'reset'])->name('password.update');
});

Route::middleware(['auth:sanctum', 'throttle:api', 'verified'])->group(function () {
    Route::prefix('user/two-factor-authentication')->group(function () {
        Route::post('/enable', [TwoFactorAuthController::class, 'enable']);
        Route::post('/confirm', [TwoFactorAuthController::class, 'confirm']);
        Route::delete('/disable', [TwoFactorAuthController::class, 'disable']);
        Route::post('/enable-email', [TwoFactorAuthController::class, 'enableEmail']);
    });

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [UserController::class, 'getAuthUser']);
    Route::put('/user/password', [UserController::class, 'updatePassword']);

    // Admin routes
    Route::prefix('admin')->middleware('role:admin,editor')->group(function () {

        Route::prefix('settings')->middleware('permission:manage_settings')->group(function () {
            Route::get('/', [SettingsController::class, 'index']);
            Route::put('/', [SettingsController::class, 'update']);
            Route::get('/cache-keys', [SettingsController::class, 'getCacheKeys']);
            Route::post('/flush-cache', [SettingsController::class, 'flushCache']);
            Route::post('/run-command', [SettingsController::class, 'runCommand']);
            Route::post('/send-test-email', [SettingsController::class, 'sendTestEmail']);
        });

        Route::prefix('user-management')
            ->middleware(['permission:manage_users'])
            ->group(function () {
                Route::get('/', [UserController::class, 'index']);

                // Create user
                Route::post('create', [UserController::class, 'store']);

                // Update user
                Route::put('update/{user}', [UserController::class, 'update']);

                // Delete user
                Route::delete('delete/{user}', [UserController::class, 'destroy']);

                // Reset password
                Route::put('reset/{user}/password', [UserController::class, 'resetPassword']);
            });

        // Editor-specific routes
        Route::middleware('permission:manage_content')->group(function () {

        });
    });
});

Route::any('{any?}', function ($any = null) {
    return response()->json(['message' => 'Unsupported API'], 404);
})
    ->middleware(['throttle:api'])
    ->where('any', '.*');
