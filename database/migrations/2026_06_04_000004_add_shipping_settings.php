<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        $rows = [
            ['key'=>'ghtk_token',       'value'=>''],  // token GHTK API
            ['key'=>'ghtk_shop_id',     'value'=>''],
            ['key'=>'ghtk_pick_address','value'=>''],  // địa chỉ lấy hàng
            ['key'=>'ghtk_pick_province','value'=>'Hà Nội'],
            ['key'=>'ghtk_pick_district','value'=>'Hoàn Kiếm'],
            ['key'=>'default_weight',   'value'=>'500'], // gram
            ['key'=>'vtp_token',        'value'=>''],  // Viettel Post
            ['key'=>'vtp_sender_phone', 'value'=>''],
            ['key'=>'vtp_sender_address','value'=>''],
        ];
        foreach ($rows as $r) {
            DB::table('admin_settings')->insertOrIgnore(array_merge($r, ['created_at'=>now(),'updated_at'=>now()]));
        }
    }
    public function down(): void {}
};
