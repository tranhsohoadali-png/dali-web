<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'deposit')) {
                $table->integer('deposit')->default(0)->after('total'); // tiền cọc đại lý (đ)
            }
            if (!Schema::hasColumn('orders', 'deposit_paid')) {
                $table->boolean('deposit_paid')->default(false)->after('deposit'); // đã nhận cọc?
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'deposit'))      $table->dropColumn('deposit');
            if (Schema::hasColumn('orders', 'deposit_paid')) $table->dropColumn('deposit_paid');
        });
    }
};
