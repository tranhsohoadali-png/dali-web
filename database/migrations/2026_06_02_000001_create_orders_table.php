<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // DALI-xxxxxx
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('customer_city');
            $table->string('customer_address');
            $table->text('note')->nullable();
            $table->enum('payment_method', ['COD','BANK'])->default('COD');
            $table->enum('payment_status', ['pending','paid','failed'])->default('pending');
            $table->enum('status', ['new','confirmed','packing','shipping','delivered','cancelled'])->default('new');
            $table->unsignedBigInteger('subtotal')->default(0);
            $table->unsignedBigInteger('discount')->default(0);
            $table->unsignedBigInteger('ship_fee')->default(0);
            $table->unsignedBigInteger('total')->default(0);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('orders'); }
};
