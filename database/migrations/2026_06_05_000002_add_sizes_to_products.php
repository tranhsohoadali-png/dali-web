<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        Schema::table('products', function (Blueprint $table) {
            // Danh sách ID các kích thước mà sản phẩm này có (JSON array)
            $table->json('size_ids')->nullable()->after('size');
        });

        // Mặc định: bật tất cả size đang có cho các sản phẩm hiện tại
        $allIds = DB::table('sizes')->where('is_active', true)->orderBy('sort_order')->pluck('id')->all();
        if (!empty($allIds)) {
            DB::table('products')->update(['size_ids' => json_encode($allIds)]);
        }
    }
    public function down(): void {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('size_ids');
        });
    }
};
