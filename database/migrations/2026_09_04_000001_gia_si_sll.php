<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Giá sỉ theo số lượng lớn (SLL): giá thấp hơn khi mua từ `sll_tu` cái trở lên. */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sp_3d', function (Blueprint $t) {
            if (!Schema::hasColumn('sp_3d', 'gia_si_sll')) $t->integer('gia_si_sll')->default(0)->after('gia_si'); // 0 = không có bậc SLL
            if (!Schema::hasColumn('sp_3d', 'sll_tu'))     $t->integer('sll_tu')->default(0)->after('gia_si_sll');  // mua từ N cái
        });
    }

    public function down(): void
    {
        Schema::table('sp_3d', function (Blueprint $t) {
            foreach (['gia_si_sll', 'sll_tu'] as $c) if (Schema::hasColumn('sp_3d', $c)) $t->dropColumn($c);
        });
    }
};
