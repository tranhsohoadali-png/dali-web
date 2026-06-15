<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('orders', function (Blueprint $table) {
            // null = đơn thường; 'pending' = khách đặt cọc, chờ shop thiết kế (AI quá tải);
            // 'delivered' = shop đã gửi bản thiết kế cho khách (qua Zalo).
            $table->string('design_status', 20)->nullable()->after('status');
        });
    }
    public function down(): void {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('design_status');
        });
    }
};
