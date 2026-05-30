<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        Schema::create('sizes', function (Blueprint $table) {
            $table->id();
            $table->string('name');                          // "40×50 cm"
            $table->string('note')->nullable();              // "không khung"
            $table->unsignedBigInteger('price')->default(0); // giá chung cho size này
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Seed các kích thước theo yêu cầu (giá mặc định – chỉnh lại trong Cài đặt)
        $now   = now();
        $sizes = [
            ['20×20 cm',   null,          99000],
            ['30×30 cm',   null,         139000],
            ['30×37,5 cm', null,         159000],
            ['40×40 cm',   null,         179000],
            ['40×50 cm',   null,         199000],
            ['50×65 cm',   null,         269000],
            ['60×75 cm',   'không khung',299000],
            ['70×90 cm',   'không khung',359000],
            ['80×90 cm',   'không khung',399000],
        ];
        $rows = [];
        foreach ($sizes as $i => [$name, $note, $price]) {
            $rows[] = [
                'name' => $name, 'note' => $note, 'price' => $price,
                'sort_order' => $i + 1, 'is_active' => true,
                'created_at' => $now, 'updated_at' => $now,
            ];
        }
        DB::table('sizes')->insert($rows);
    }
    public function down(): void { Schema::dropIfExists('sizes'); }
};
