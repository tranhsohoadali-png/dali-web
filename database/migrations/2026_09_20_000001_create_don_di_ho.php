<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Đơn "đi đơn hộ" cho đại lý bán trên sàn TMĐT (Shopee/Lazada/TikTok...).
 * Đại lý chọn sản phẩm + cấp học + tên in riêng + ghi chú, rồi TẢI NHÃN/HOÁ ĐƠN
 * VẬN CHUYỂN lên để xưởng in & dán nhãn gửi hộ. Không thu tiền online — chỉ ghi
 * tổng giá sỉ để hai bên đối soát.
 *
 * Nhãn vận chuyển chứa thông tin khách cuối (tên, địa chỉ, SĐT) nên lưu ở đĩa
 * PRIVATE (storage/app/private/di-ho/...), chỉ tải qua trang quản trị đã đăng nhập.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('don_di_ho', function (Blueprint $t) {
            $t->id();
            $t->string('ma')->unique();                 // DHyymmdd-XXXXXX
            $t->unsignedBigInteger('dai_ly_id')->index();
            $t->string('dai_ly_ten');                    // tên đại lý (chép lại)
            $t->string('dai_ly_sdt')->nullable();
            $t->json('chi_tiet');                        // [{slug,ten,bien_the,qty,cap_hoc,ten_in,ghi_chu,don_gia_si,thanh_tien}]
            $t->unsignedInteger('so_luong')->default(0); // tổng số lượng
            $t->unsignedInteger('tong_si')->default(0);  // tổng giá sỉ tham khảo
            $t->string('nhan_vc_path')->nullable();      // đường dẫn file nhãn (đĩa private)
            $t->string('nhan_vc_ten')->nullable();       // tên file gốc
            $t->string('nhan_vc_mime')->nullable();
            $t->string('tt')->default('moi');            // moi / da_in / da_gui / huy
            $t->text('ghi_chu')->nullable();             // ghi chú chung của đại lý
            $t->string('ma_vc')->nullable();             // mã vận đơn (nhập tay khi cần)
            $t->string('vc')->nullable();                // đơn vị vận chuyển
            $t->timestamp('gui_luc')->nullable();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('don_di_ho');
    }
};
