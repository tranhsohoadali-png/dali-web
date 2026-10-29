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
    // Đại lý: đăng nhập để xem giá sỉ (giá sỉ chỉ trả cho token hợp lệ ở /catalog)
    Route::post('dai-ly/login',  [\App\Http\Controllers\Api3dController::class, 'dealerLogin']);
    Route::get('dai-ly/me',      [\App\Http\Controllers\Api3dController::class, 'dealerMe']);
    Route::post('dai-ly/logout', [\App\Http\Controllers\Api3dController::class, 'dealerLogout']);
    Route::get('dai-ly/phien',   [\App\Http\Controllers\Api3dController::class, 'dealerPhien']);          // các thiết bị đang đăng nhập
    Route::post('dai-ly/dang-xuat-khac', [\App\Http\Controllers\Api3dController::class, 'dealerDangXuatKhac']);
    Route::get('dai-ly/tai-anh/{slug}', [\App\Http\Controllers\Api3dController::class, 'dealerTaiAnh']);
    // Đại lý: đi đơn hộ (đơn TMĐT) — gửi đơn + tải nhãn vận chuyển; xem đơn của mình
    Route::post('dai-ly/di-don', [\App\Http\Controllers\Api3dController::class, 'dealerDiDonTao']);
    Route::get('dai-ly/di-don',  [\App\Http\Controllers\Api3dController::class, 'dealerDiDonList']);
    Route::get('dai-ly/dashboard', [\App\Http\Controllers\Api3dController::class, 'dealerDashboard']); // bảng điều khiển đại lý
    // Giới thiệu & hoa hồng (1 cấp) — ví dùng bảng affiliates, rút qua withdrawals
    Route::get('dai-ly/hoa-hong',  [\App\Http\Controllers\Api3dController::class, 'dealerHoaHong']);
    Route::post('dai-ly/rut-tien', [\App\Http\Controllers\Api3dController::class, 'dealerRutTien']);
    Route::post('dai-ly/dang-ky',  [\App\Http\Controllers\Api3dController::class, 'dealerDangKy']);  // tự đăng ký, chờ duyệt
    Route::get('dai-ly/di-don/chi-tiet', [\App\Http\Controllers\Api3dController::class, 'dealerDiDonChiTiet']);
    Route::get('dai-ly/di-don/file',     [\App\Http\Controllers\Api3dController::class, 'dealerDiDonFile']);
    Route::post('dai-ly/di-don/doc-nhan-vc', [\App\Http\Controllers\Api3dController::class, 'dealerDocNhanVc']); // AI đọc mã vận đơn từ nhãn
    Route::post('dai-ly/doc-mon-anh', [\App\Http\Controllers\Api3dController::class, 'dealerDocMonAnh']); // AI đọc môn từ ảnh
    Route::post('dai-ly/xac-nhan-vc', [\App\Http\Controllers\Api3dController::class, 'dealerXacNhanVc']); // ĐL xác nhận VC nhận hàng (cũ)
    Route::post('dai-ly/danh-dau-gui', [\App\Http\Controllers\Api3dController::class, 'dealerDanhDauGui']); // ĐL đánh dấu Đã gửi
    Route::post('dai-ly/xoa-don',     [\App\Http\Controllers\Api3dController::class, 'dealerXoaDon']);   // ĐL tự xoá đơn (chỉ khi tt=moi)
});

/*
 * Cổng seller POD (/api/pod/*). Trang cổng ở /pod cùng tên miền. Token header X-Pod-Token.
 * Mẫu riêng của seller khác luôn trả 404 (PodMau::hienCho).
 */
Route::prefix('pod')->middleware('throttle:120,1')->group(function () {
    Route::post('dang-nhap',       [\App\Http\Controllers\PodApiController::class, 'dangNhap']);
    Route::get('toi',              [\App\Http\Controllers\PodApiController::class, 'toi']);
    Route::post('dong-y',          [\App\Http\Controllers\PodApiController::class, 'dongY']);
    Route::post('dang-xuat',       [\App\Http\Controllers\PodApiController::class, 'dangXuat']);
    Route::get('mau',              [\App\Http\Controllers\PodApiController::class, 'mau']);
    Route::get('mau/{ma}',         [\App\Http\Controllers\PodApiController::class, 'mauChiTiet']);
    Route::get('anh/{ma}/{i}',     [\App\Http\Controllers\PodApiController::class, 'anh'])
        ->whereNumber('i')->middleware('signed:relative')->name('pod.anh');   // link ảnh có chữ ký, 10 phút
    Route::get('anh-kt/{ma}/{mat}/{kieu?}', [\App\Http\Controllers\PodApiController::class, 'anhKt'])
        ->whereIn('mat', ['truoc', 'ben', 'bia'])->where('kieu', '[a-z0-9-]+')->middleware('signed:relative')->name('pod.anh-kt'); // hình chiếu + bìa từng kiểu
    Route::get('noi-giao',         [\App\Http\Controllers\PodApiController::class, 'noiGiao']);
    Route::post('noi-giao',        [\App\Http\Controllers\PodApiController::class, 'noiGiaoLuu']);
    Route::delete('noi-giao/{id}', [\App\Http\Controllers\PodApiController::class, 'noiGiaoXoa'])->whereNumber('id');
    Route::get('don',              [\App\Http\Controllers\PodApiController::class, 'don']);
    Route::post('don',             [\App\Http\Controllers\PodApiController::class, 'donTao'])->middleware('throttle:30,1');
    Route::post('don/{ma}/huy',    [\App\Http\Controllers\PodApiController::class, 'donHuy']);
    Route::get('vi',               [\App\Http\Controllers\PodApiController::class, 'vi']);
});
