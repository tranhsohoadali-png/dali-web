<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();                    // DALI10
            $table->enum('type', ['percent','fixed'])->default('percent');
            $table->unsignedInteger('value');                    // 10 (%) or 50000 (đ)
            $table->unsignedBigInteger('min_order')->default(0); // đơn tối thiểu
            $table->unsignedInteger('max_uses')->nullable();     // null = vô hạn
            $table->unsignedInteger('used_count')->default(0);
            $table->date('expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('coupons'); }
};
