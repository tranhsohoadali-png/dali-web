<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Đại lý được tích "luôn nhận giá SLL" -> hưởng giá số-lượng-lớn không cần đủ số lượng. */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('dai_ly', 'sll_luon')) {
            Schema::table('dai_ly', fn (Blueprint $t) => $t->boolean('sll_luon')->default(false)->after('hien'));
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('dai_ly', 'sll_luon')) {
            Schema::table('dai_ly', fn (Blueprint $t) => $t->dropColumn('sll_luon'));
        }
    }
};
