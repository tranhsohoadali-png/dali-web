<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('coupon_code')->nullable()->after('note');
            $table->unsignedBigInteger('coupon_discount')->default(0)->after('coupon_code');
        });
    }
    public function down(): void {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['coupon_code','coupon_discount']);
        });
    }
};
