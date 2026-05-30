<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('affiliates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('code')->unique();             // REF CODE – DALI_TUAN
            $table->decimal('commission_rate', 5, 2)->default(5.00); // % hoa hồng
            $table->unsignedBigInteger('total_earned')->default(0);  // đ đã kiếm được
            $table->unsignedBigInteger('total_paid')->default(0);    // đ đã thanh toán
            $table->unsignedInteger('total_orders')->default(0);
            $table->string('bank_name')->nullable();
            $table->string('bank_acc')->nullable();
            $table->string('bank_owner')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('affiliates'); }
};
