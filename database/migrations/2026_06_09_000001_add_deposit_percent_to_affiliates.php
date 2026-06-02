<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('affiliates', function (Blueprint $table) {
            // % cọc riêng cho đại lý. NULL = dùng mức chung (agent_deposit_percent).
            // 0 = miễn cọc (lên đơn thẳng, không qua bước đặt cọc).
            $table->unsignedTinyInteger('deposit_percent')->nullable()->after('commission_rate');
        });
    }

    public function down(): void
    {
        Schema::table('affiliates', function (Blueprint $table) {
            $table->dropColumn('deposit_percent');
        });
    }
};
