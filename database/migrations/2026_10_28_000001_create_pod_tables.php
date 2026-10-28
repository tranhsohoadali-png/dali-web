<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Cổng seller POD (GĐ1): seller Việt bán Etsy/Amazon/TikTok US đặt xưởng in.
 * Mẫu POD nằm ở bảng RIÊNG (pod_mau), KHÔNG dùng sp_3d -> mẫu độc quyền không thể lọt
 * vào catalog/sitemap của web bán lẻ. Một cột quyết định ai thấy: pod_mau.chu_seller_id
 * (NULL = Kho chung, có id = Kho riêng của seller đó).
 * Ví = sổ chỉ thêm dòng (pod_vi): số dư = tổng so_tien, không sửa dòng cũ.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('pod_seller')) {
            Schema::create('pod_seller', function (Blueprint $t) {
                $t->id();
                $t->string('ma', 12)->unique();            // mã seller, dùng làm nội dung chuyển khoản nạp ví
                $t->string('ten', 120);
                $t->string('sdt', 20)->unique();
                $t->string('matkhau');
                $t->boolean('hien')->default(true);         // false = khoá
                $t->timestamp('dong_y_luc')->nullable();    // lúc bấm đồng ý điều khoản bảo mật
                $t->timestamp('dang_nhap_luc')->nullable();
                $t->string('ghi_chu', 500)->nullable();
                $t->timestamps();
            });
        }
        if (!Schema::hasTable('pod_phien')) {
            Schema::create('pod_phien', function (Blueprint $t) {
                $t->id();
                $t->unsignedBigInteger('seller_id')->index();
                $t->string('token_hash', 64)->unique();     // chỉ lưu băm SHA-256 của token
                $t->string('thiet_bi', 120)->nullable();
                $t->string('ip', 45)->nullable();
                $t->timestamp('dung_luc')->nullable();
                $t->timestamps();
            });
        }
        if (!Schema::hasTable('pod_mau')) {
            Schema::create('pod_mau', function (Blueprint $t) {
                $t->id();
                $t->string('ma', 10)->unique();             // mã ngẫu nhiên, không dò được bằng cách tăng số
                $t->string('ten', 160);
                $t->string('nhom', 60)->nullable();         // khuôn bánh, móc khoá, biển tên…
                $t->text('mo_ta')->nullable();
                $t->json('anh')->nullable();                // đường dẫn trên đĩa PRIVATE (pod/anh/…)
                $t->unsignedBigInteger('chu_seller_id')->nullable()->index(); // NULL = Kho chung
                $t->unsignedInteger('phi_ca_nhan')->default(0);  // phí cá nhân hoá, 1 lần/dòng đơn có chữ riêng
                $t->unsignedInteger('phi_thiet_ke')->default(0); // phí seller đã trả để làm mẫu riêng
                $t->boolean('hien')->default(true);
                $t->timestamps();
            });
        }
        if (!Schema::hasTable('pod_bien_the')) {
            Schema::create('pod_bien_the', function (Blueprint $t) {
                $t->id();
                $t->unsignedBigInteger('mau_id')->index();
                $t->string('ten', 120);                     // vd "Tem + khuôn cắt · 3 inch"
                $t->decimal('gram', 8, 1);                  // gram trên slicer, gồm support + nhựa xả
                $t->string('mau_sac', 300)->nullable();     // màu cho phép, phẩy ngăn cách; trống = theo mẫu
                $t->string('file_in', 300)->nullable();     // đường dẫn file trên MÁY XƯỞNG, chỉ admin thấy
                $t->unsignedInteger('thu_tu')->default(0);
                $t->timestamps();
            });
        }
        if (!Schema::hasTable('pod_noi_giao')) {
            Schema::create('pod_noi_giao', function (Blueprint $t) {
                $t->id();
                $t->unsignedBigInteger('seller_id')->index();
                $t->string('ten', 120);                     // tên kho gom / line bay
                $t->string('dia_chi', 300);
                $t->string('sdt', 20)->nullable();
                $t->string('ghi_chu', 300)->nullable();
                $t->timestamps();
            });
        }
        if (!Schema::hasTable('pod_don')) {
            Schema::create('pod_don', function (Blueprint $t) {
                $t->id();
                $t->string('ma', 20)->unique();
                $t->unsignedBigInteger('seller_id')->index();
                $t->json('noi_giao')->nullable();           // chụp lại nơi giao lúc đặt
                $t->string('ma_don_san', 60)->nullable();   // mã đơn Etsy/Amazon để đối chiếu
                $t->string('ghi_chu', 1000)->nullable();
                $t->string('trang_thai', 20)->default('cho_nap')->index(); // cho_nap|cho_in|dang_in|da_giao|da_huy
                $t->unsignedInteger('tong_tien')->default(0);
                $t->boolean('da_tru')->default(false);      // đã trừ ví chưa (để biết có phải hoàn khi huỷ)
                $t->string('ma_van_don', 60)->nullable();
                $t->string('nhan', 200)->nullable();        // nhãn line bay, đĩa PRIVATE
                $t->timestamp('giao_luc')->nullable();
                $t->timestamp('huy_luc')->nullable();
                $t->timestamps();
            });
        }
        if (!Schema::hasTable('pod_don_dong')) {
            Schema::create('pod_don_dong', function (Blueprint $t) {
                $t->id();
                $t->unsignedBigInteger('don_id')->index();
                $t->unsignedBigInteger('mau_id');
                $t->unsignedBigInteger('bien_the_id');
                $t->string('ten_mau', 160);                 // chụp lại tên lúc đặt
                $t->string('ten_bien_the', 120);
                $t->string('mau_sac', 60)->nullable();
                $t->unsignedInteger('so_luong');
                $t->decimal('gram', 8, 1);                  // gram 1 cái lúc đặt
                $t->unsignedInteger('don_gia_gram');        // chốt giá lúc đặt
                $t->string('chu_ca_nhan', 300)->nullable();
                $t->unsignedInteger('phi_ca_nhan')->default(0);
                $t->unsignedInteger('thanh_tien');
                $t->timestamps();
            });
        }
        if (!Schema::hasTable('pod_vi')) {
            Schema::create('pod_vi', function (Blueprint $t) {
                $t->id();
                $t->unsignedBigInteger('seller_id')->index();
                $t->string('loai', 20);                     // nap|tru|hoan|dieu_chinh
                $t->integer('so_tien');                     // + vào ví, − ra khỏi ví
                $t->unsignedBigInteger('don_id')->nullable()->index();
                $t->string('ghi_chu', 300)->nullable();
                $t->string('nguoi_tao', 60)->nullable();    // 'seller' | 'admin' | 'he_thong'
                $t->timestamps();
            });
        }
        if (!Schema::hasTable('pod_nhat_ky')) {
            Schema::create('pod_nhat_ky', function (Blueprint $t) {
                $t->id();
                $t->unsignedBigInteger('mau_id')->nullable()->index();
                $t->string('hanh_dong', 40);                // xem|tao|sua|doi_chu|chuyen_kho_chung|xoa|xem_anh
                $t->string('chi_tiet', 500)->nullable();
                $t->string('nguoi', 60)->nullable();
                $t->string('ip', 45)->nullable();
                $t->timestamps();
            });
        }
    }

    public function down(): void
    {
        foreach (['pod_nhat_ky', 'pod_vi', 'pod_don_dong', 'pod_don', 'pod_noi_giao', 'pod_bien_the', 'pod_mau', 'pod_phien', 'pod_seller'] as $t) {
            Schema::dropIfExists($t);
        }
    }
};
