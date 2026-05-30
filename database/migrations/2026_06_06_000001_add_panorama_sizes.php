<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        $now = now();
        $sizes = [
            ['40×80 cm',   'panorama', 359000],
            ['50×100 cm',  'panorama', 469000],
            ['60×120 cm',  'panorama', 629000],
            ['70×140 cm',  'panorama', 829000],
            ['80×160 cm',  'panorama', 1090000],
        ];
        $order = 20;
        foreach ($sizes as [$name, $note, $price]) {
            // tránh trùng nếu chạy lại
            $exists = DB::table('sizes')->where('name', $name)->exists();
            if (!$exists) {
                DB::table('sizes')->insert([
                    'name' => $name, 'note' => $note, 'price' => $price,
                    'sort_order' => $order++, 'is_active' => true,
                    'created_at' => $now, 'updated_at' => $now,
                ]);
            }
        }
    }
    public function down(): void {
        DB::table('sizes')->whereIn('name', ['40×80 cm','50×100 cm','60×120 cm','70×140 cm','80×160 cm'])->delete();
    }
};
