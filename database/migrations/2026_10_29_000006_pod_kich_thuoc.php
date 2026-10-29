<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Ảnh kích thước các chiều, đổi số theo cỡ khách chọn.
 * pod_bien_the.kich_thuoc: {"rong","cao","day"} mm của cỡ đó; móc khoá tên (bề ngang tuỳ tên) thì {"ti_le","day"}.
 * pod_mau.kich_thuoc: mẫu dạng khối có ảnh hình chiếu riêng {"truoc": rel, "ben": rel} (đĩa private, ảnh trong suốt
 * cắt sát vật: nhìn trước = rộng × cao, nhìn bên = dày × cao). Mẫu khắc tên vẽ hình chiếu từ ảnh xem trước.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pod_bien_the', function (Blueprint $t) {
            if (!Schema::hasColumn('pod_bien_the', 'kich_thuoc')) $t->json('kich_thuoc')->nullable();
        });
        Schema::table('pod_mau', function (Blueprint $t) {
            if (!Schema::hasColumn('pod_mau', 'kich_thuoc')) $t->json('kich_thuoc')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('pod_bien_the', fn (Blueprint $t) => $t->dropColumn('kich_thuoc'));
        Schema::table('pod_mau', fn (Blueprint $t) => $t->dropColumn('kich_thuoc'));
    }
};
