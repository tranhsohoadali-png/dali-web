<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('size')->nullable();           // "40×50 cm"
            $table->integer('colors_count')->default(36); // số màu
            $table->unsignedBigInteger('price');          // giá gốc (đồng)
            $table->unsignedBigInteger('sale_price')->nullable(); // giá khuyến mãi
            $table->string('badge')->nullable();          // "Mới", "Hot", "-20%"
            $table->string('badge_type')->default('default'); // default|new|hot|sale
            $table->string('main_image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->integer('sold_count')->default(0);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('products'); }
};
