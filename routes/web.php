<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebsiteController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\HeroSectionController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\AffiliateController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\WithdrawalController;
use App\Http\Controllers\CtvController;

// ─── FRONTEND ───────────────────────────────────
Route::get('/',                  [WebsiteController::class, 'index'])->name('home');
Route::get('/san-pham',          [WebsiteController::class, 'products'])->name('products');
Route::get('/chu-de/{category:slug}', [WebsiteController::class, 'categoryCombo'])->name('category');
Route::get('/san-pham/{product:slug}', [WebsiteController::class, 'product'])->name('product');
Route::get('/tra-cuu-don-hang',  [WebsiteController::class, 'trackOrder'])->name('track-order');
Route::post('/dat-hang',         [WebsiteController::class, 'placeOrder'])->name('place-order');
// ── CART ──
Route::get('/gio-hang',                    [WebsiteController::class, 'cart'])->name('cart');
Route::post('/gio-hang/them',              [WebsiteController::class, 'addToCart'])->name('cart.add');
Route::post('/gio-hang/cap-nhat',          [WebsiteController::class, 'updateCart'])->name('cart.update');
Route::post('/gio-hang/xoa',               [WebsiteController::class, 'removeFromCart'])->name('cart.remove');
Route::get('/gio-hang/so-luong',           [WebsiteController::class, 'cartCount'])->name('cart.count');
Route::post('/gio-hang/dat-hang',          [WebsiteController::class, 'checkoutFromCart'])->name('cart.checkout');

// ── BLOG ──
Route::get('/blog',                        [WebsiteController::class, 'blog'])->name('blog');
Route::get('/blog/{post:slug}',            [WebsiteController::class, 'blogPost'])->name('blog.post');

// ── AFFILIATE TRACKING ──
Route::get('/ref/{code}',                  [WebsiteController::class, 'trackAffiliate'])->name('affiliate.track');

Route::post('/kiem-tra-ma-giam-gia', [WebsiteController::class, 'checkCoupon'])->name('check-coupon');
Route::post('/danh-gia',               [WebsiteController::class, 'submitReview'])->name('submit-review');
Route::get('/sitemap.xml',             [WebsiteController::class, 'sitemap'])->name('sitemap');
Route::post('/dat-hang/confirm',  [WebsiteController::class, 'confirmPayment'])->name('place-order.confirm');

// ─── CỔNG CỘNG TÁC VIÊN (CTV) ───────────────────
Route::prefix('ctv')->name('ctv.')->group(function () {
    Route::get('dang-nhap',  [CtvController::class, 'showLogin'])->name('login');
    Route::post('dang-nhap', [CtvController::class, 'login'])->name('login.post');
    Route::get('dang-xuat',  [CtvController::class, 'logout'])->name('logout');

    Route::middleware('ctv.auth')->group(function () {
        Route::get('/',           [CtvController::class, 'dashboard'])->name('dashboard');
        Route::get('don-hang',    [CtvController::class, 'orders'])->name('orders');
        Route::get('len-don',     [CtvController::class, 'createOrder'])->name('order.create');
        Route::post('len-don',    [CtvController::class, 'storeOrder'])->name('order.store');
        Route::get('rut-tien',    [CtvController::class, 'withdrawPage'])->name('withdraw.page');
        Route::post('rut-tien',   [CtvController::class, 'withdraw'])->name('withdraw');
        Route::get('ho-so',       [CtvController::class, 'profile'])->name('profile');
        Route::post('ho-so',      [CtvController::class, 'updateProfile'])->name('profile.update');
    });
});

// ─── ADMIN AUTH ─────────────────────────────────
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login',  [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.post');
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
});

// ─── ADMIN (protected) ──────────────────────────
Route::prefix('admin')->name('admin.')->middleware('admin.auth')->group(function () {
    Route::get('/',         [DashboardController::class, 'index'])->name('dashboard');
    Route::get('dashboard', [DashboardController::class, 'index']);

    // Hero
    Route::get('hero',          [HeroSectionController::class, 'edit'])->name('hero.edit');
    Route::post('hero/update',  [HeroSectionController::class, 'update'])->name('hero.update');

    // Danh mục
    Route::resource('categories', CategoryController::class)->except(['show']);

    // Sản phẩm
    Route::resource('products', ProductController::class)->except(['show']);

    // Đơn hàng
    Route::get('orders',                       [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/export',                [OrderController::class, 'export'])->name('orders.export');
    Route::get('orders/{order}',               [OrderController::class, 'show'])->name('orders.show');
    Route::post('orders/{order}/status',       [OrderController::class, 'updateStatus'])->name('orders.status');
    Route::delete('orders/{order}',            [OrderController::class, 'destroy'])->name('orders.destroy');

    // Cài đặt

    // Blog
    Route::resource('posts', PostController::class)->except(['show']);

    // CTV / Affiliate
    Route::resource('affiliates', AffiliateController::class)->except(['show']);
    Route::get('affiliates/{affiliate}/detail',   [AffiliateController::class, 'show'])->name('affiliates.show');
    Route::post('affiliates/{affiliate}/paid',    [AffiliateController::class, 'markPaid'])->name('affiliates.paid');

    // Yêu cầu rút tiền
    Route::get('withdrawals',                       [WithdrawalController::class, 'index'])->name('withdrawals.index');
    Route::post('withdrawals/{withdrawal}/approve', [WithdrawalController::class, 'approve'])->name('withdrawals.approve');
    Route::post('withdrawals/{withdrawal}/reject',  [WithdrawalController::class, 'reject'])->name('withdrawals.reject');

    // Mã giảm giá
    Route::resource('coupons', CouponController::class)->except(['show']);

    // Đánh giá sản phẩm
    Route::get('reviews',                  [ReviewController::class, 'index'])->name('reviews.index');
    Route::post('reviews/{review}/approve',[ReviewController::class, 'approve'])->name('reviews.approve');
    Route::delete('reviews/{review}',      [ReviewController::class, 'destroy'])->name('reviews.destroy');

        Route::get('settings',   [SettingsController::class, 'index'])->name('settings');
    Route::post('settings',  [SettingsController::class, 'update'])->name('settings.update');
    Route::post('settings/sizes', [SettingsController::class, 'updateSizes'])->name('settings.sizes');
});
