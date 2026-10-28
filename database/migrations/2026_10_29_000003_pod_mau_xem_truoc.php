<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Ảnh xem trước theo tên cho mẫu cá nhân hoá (vd ornament khắc tên): khung 2D lấy từ file 3D thật
 * + ô tên (tâm, rộng, cao, góc nghiêng). Trình duyệt vẽ khung + tên bằng font ngay khi seller gõ.
 * {"loai":"ten","font":"Pacifico","toi_da":12,"w":90,"h":103.6,"khung":"<SVG path mm>",
 *  "o_ten":{"cx":50.7,"cy":57.7,"w":75.9,"h":19.7,"goc":-10.6}}
 * Có xem_truoc loại "ten" thì đặt đơn BẮT BUỘC có tên.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pod_mau', function (Blueprint $t) {
            if (!Schema::hasColumn('pod_mau', 'xem_truoc')) $t->json('xem_truoc')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('pod_mau', fn (Blueprint $t) => $t->dropColumn('xem_truoc'));
    }
};
