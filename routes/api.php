<?php

use App\Http\Controllers\Api\IntegrationController;
use Illuminate\Support\Facades\Route;

/*
 * API tích hợp với app marketing (social-suite).
 * Tiền tố /api tự thêm bởi Laravel. Tất cả bảo vệ bằng token (integration.auth).
 */
Route::prefix('integration')->middleware(['integration.auth', 'throttle:60,1'])->group(function () {
    Route::get('ping',         [IntegrationController::class, 'ping']);
    Route::get('products',     [IntegrationController::class, 'products']);
    Route::get('catalog-meta', [IntegrationController::class, 'catalogMeta']);
    Route::post('products',    [IntegrationController::class, 'createProduct']);
    Route::get('leads',        [IntegrationController::class, 'leads']);
    Route::post('posts',       [IntegrationController::class, 'createPost']);
});

/*
 * API công khai cho web bán hàng 3d.tranhdali.vn (khu Xưởng in 3D).
 * Không cần token: đây là chỗ web khách gọi. Giá chỉ tính ở server.
 */
Route::prefix('3d')->group(function () {
    Route::get('catalog',       [\App\Http\Controllers\Api3dController::class, 'catalog']);
    Route::post('quote',        [\App\Http\Controllers\Api3dController::class, 'quote']);
    Route::post('checkout',     [\App\Http\Controllers\Api3dController::class, 'checkout']);
    Route::post('event',        [\App\Http\Controllers\Api3dController::class, 'event']);
    Route::post('order-lookup', [\App\Http\Controllers\Api3dController::class, 'orderLookup']);
});
