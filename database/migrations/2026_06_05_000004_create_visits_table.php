<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->string('ip', 45)->nullable();
            $table->string('path')->nullable();
            $table->string('referer')->nullable();
            $table->date('date')->index();
            $table->timestamps();
            $table->index(['ip', 'date']);
        });
    }
    public function down(): void { Schema::dropIfExists('visits'); }
};
