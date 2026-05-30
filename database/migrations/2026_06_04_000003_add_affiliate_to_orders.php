<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('affiliate_code')->nullable()->after('coupon_discount');
            $table->unsignedBigInteger('affiliate_commission')->default(0)->after('affiliate_code');
        });
    }
    public function down(): void {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['affiliate_code','affiliate_commission']);
        });
    }
};
