<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hero_sections', function (Blueprint $table) {
            // Danh sách ảnh slideshow thêm (ngoài main_image). Lưu mảng đường dẫn.
            $table->json('gallery')->nullable()->after('main_image');
        });
    }

    public function down(): void
    {
        Schema::table('hero_sections', function (Blueprint $table) {
            $table->dropColumn('gallery');
        });
    }
};
