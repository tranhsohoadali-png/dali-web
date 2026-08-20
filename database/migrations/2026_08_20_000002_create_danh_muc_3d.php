<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Danh mục 3 cấp cho Xưởng in 3D: Nhóm -> Danh mục -> Sản phẩm.
 * - nhom_3d: nhóm lớn (Góc học tập, Quà tặng…)
 * - danh_muc_3d: danh mục con thuộc 1 nhóm
 * - sp_3d.danh_muc_id: mỗi sản phẩm thuộc 1 danh mục (giữ cột `cat` chuỗi làm nhãn phụ)
 * Seed sẵn từ dữ liệu hiện có để 3 sản phẩm không bị rớt khỏi trang chủ.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nhom_3d', function (Blueprint $t) {
            $t->id();
            $t->string('ten');
            $t->string('slug')->unique();
            $t->string('mo_ta')->nullable();
            $t->integer('thu_tu')->default(0);
            $t->boolean('hien')->default(true);
            $t->timestamps();
        });

        Schema::create('danh_muc_3d', function (Blueprint $t) {
            $t->id();
            $t->foreignId('nhom_id')->constrained('nhom_3d')->cascadeOnDelete();
            $t->string('ten');
            $t->string('slug');
            $t->string('icon')->nullable();
            $t->integer('thu_tu')->default(0);
            $t->boolean('hien')->default(true);
            $t->timestamps();
        });

        if (!Schema::hasColumn('sp_3d', 'danh_muc_id')) {
            Schema::table('sp_3d', function (Blueprint $t) {
                $t->unsignedBigInteger('danh_muc_id')->nullable()->after('cat');
            });
        }

        // ---- Seed từ dữ liệu hiện có ----
        $now = now();
        $nhomHoc = DB::table('nhom_3d')->insertGetId(['ten' => 'Góc học tập', 'slug' => 'goc-hoc-tap', 'mo_ta' => 'Đồ dùng học tập in 3D cho bé', 'thu_tu' => 1, 'hien' => true, 'created_at' => $now, 'updated_at' => $now]);
        $nhomQua = DB::table('nhom_3d')->insertGetId(['ten' => 'Quà tặng', 'slug' => 'qua-tang', 'mo_ta' => 'Món quà nhỏ in 3D độc đáo', 'thu_tu' => 2, 'hien' => true, 'created_at' => $now, 'updated_at' => $now]);

        $dmBang = DB::table('danh_muc_3d')->insertGetId(['nhom_id' => $nhomHoc, 'ten' => 'Bảng học tập', 'slug' => 'bang-hoc-tap', 'icon' => '📅', 'thu_tu' => 1, 'hien' => true, 'created_at' => $now, 'updated_at' => $now]);
        $dmStem = DB::table('danh_muc_3d')->insertGetId(['nhom_id' => $nhomHoc, 'ten' => 'Mô hình STEM', 'slug' => 'mo-hinh-stem', 'icon' => '⚗️', 'thu_tu' => 2, 'hien' => true, 'created_at' => $now, 'updated_at' => $now]);
        $dmBook = DB::table('danh_muc_3d')->insertGetId(['nhom_id' => $nhomQua, 'ten' => 'Bookmark & thẻ', 'slug' => 'bookmark-the', 'icon' => '🔖', 'thu_tu' => 1, 'hien' => true, 'created_at' => $now, 'updated_at' => $now]);

        DB::table('sp_3d')->where('slug', 'bang-tkb')->update(['danh_muc_id' => $dmBang]);
        DB::table('sp_3d')->where('slug', 'mo-hinh-phan-tu')->update(['danh_muc_id' => $dmStem]);
        DB::table('sp_3d')->where('slug', 'bookmark')->update(['danh_muc_id' => $dmBook]);
    }

    public function down(): void
    {
        if (Schema::hasColumn('sp_3d', 'danh_muc_id')) {
            Schema::table('sp_3d', fn (Blueprint $t) => $t->dropColumn('danh_muc_id'));
        }
        Schema::dropIfExists('danh_muc_3d');
        Schema::dropIfExists('nhom_3d');
    }
};
