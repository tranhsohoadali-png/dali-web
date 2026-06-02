<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('affiliates', function (Blueprint $table) {
            if (!Schema::hasColumn('affiliates', 'type')) {
                $table->string('type', 20)->default('ctv')->after('code'); // ctv | agent
            }
        });

        if (!Schema::hasTable('agent_prices')) {
            Schema::create('agent_prices', function (Blueprint $table) {
                $table->id();
                $table->foreignId('affiliate_id')->constrained()->cascadeOnDelete();
                $table->foreignId('size_id')->constrained()->cascadeOnDelete();
                $table->integer('price')->default(0); // giá sỉ đại lý cho khổ này
                $table->timestamps();
                $table->unique(['affiliate_id', 'size_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('agent_prices');
        Schema::table('affiliates', function (Blueprint $table) {
            if (Schema::hasColumn('affiliates', 'type')) $table->dropColumn('type');
        });
    }
};
