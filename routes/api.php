<?php

use App\Http\Controllers\Api\IntegrationController;
use Illuminate\Support\Facades\Route;

/*
 * API tích hợp với app marketing (social-suite).
 * Tiền tố /api tự thêm bởi Laravel. Tất cả bảo vệ bằng token (integration.auth).
 */
Route::prefix('integration')->middleware(['integration.auth', 'throttle:60,1'])->group(function () {
    Route::get('ping',     [IntegrationController::class, 'ping']);
    Route::get('products', [IntegrationController::class, 'products']);
    Route::get('leads',    [IntegrationController::class, 'leads']);
    Route::post('posts',   [IntegrationController::class, 'createPost']);
});
