<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Lưu KẾT QUẢ ĐỌC MÔN theo mã băm nội dung ảnh (sha256): AI đã đọc ảnh nào thì lần sau trả lại ngay, không tốn phí API.
 * Cùng một ảnh: đại lý đọc lúc đặt đơn -> xưởng mở đơn đọc lại vẫn trúng (ảnh ghi chú lưu nguyên byte bằng storeAs).
 * nguon = 'ai' (chốt, chỉ đọc lại khi bấm "Đọc lại") | 'may' (tạm: lần bấm sau vẫn thử AI trước).
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('doc_mon_luu')) {
            Schema::create('doc_mon_luu', function (Blueprint $t) {
                $t->id();
                $t->string('sha256', 64)->unique();
                $t->string('nguon', 10);                    // ai | may
                $t->string('model', 60)->nullable();
                $t->json('ket_qua');                        // {ok, mon[], tong, text, khong_ro[], khong_phai_tkb?, error?}
                $t->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('doc_mon_luu');
    }
};
