<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        $rows = [
            ['key'=>'ga_id',           'value'=>''],
            ['key'=>'fb_pixel_id',     'value'=>''],
            ['key'=>'zalo_oa_id',      'value'=>''],
            ['key'=>'meta_title',      'value'=>'DALI – Tranh Tô Màu Số Hóa'],
            ['key'=>'meta_description','value'=>'Bộ tranh tô màu số hóa DALI – ai cũng có thể tạo ra kiệt tác hội họa của riêng mình.'],
            ['key'=>'meta_keywords',   'value'=>'tranh tô màu số hóa, paint by numbers, DALI tranh'],
            ['key'=>'mail_from',       'value'=>''],
            ['key'=>'mail_name',       'value'=>'DALI Tranh Tô Màu'],
            ['key'=>'free_ship_from',  'value'=>'299000'],
            ['key'=>'ship_fee',        'value'=>'30000'],
            ['key'=>'discount_bank',   'value'=>'5'],
        ];
        foreach ($rows as $row) {
            DB::table('admin_settings')->insertOrIgnore(array_merge($row, ['created_at'=>now(),'updated_at'=>now()]));
        }
    }
    public function down(): void {}
};
