<?php
use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Đổi app timezone UTC -> Asia/Ho_Chi_Minh. Dữ liệu CŨ lưu theo UTC nên dịch
 * các mốc thời gian +7 giờ để hiển thị đúng giờ Việt Nam. Dùng Carbon (PHP)
 * nên chạy được cả MySQL (production) lẫn SQLite (local). Chạy 1 lần.
 */
return new class extends Migration {
    private array $tables = [
        'orders', 'order_items', 'design_leads', 'design_quotas', 'reviews',
        'posts', 'affiliates', 'withdrawals', 'coupons', 'products',
        'categories', 'sizes', 'hero_sections', 'agent_prices', 'users',
    ];
    private array $extraCols = [
        'orders' => ['vtp_status_at'],
        'posts'  => ['published_at'],
    ];

    private function shift(int $hours): void
    {
        foreach ($this->tables as $t) {
            if (!Schema::hasTable($t) || !Schema::hasColumn($t, 'id')) continue;
            $cols = array_values(array_filter(
                array_merge(['created_at', 'updated_at'], $this->extraCols[$t] ?? []),
                fn ($c) => Schema::hasColumn($t, $c)
            ));
            if (!$cols) continue;

            DB::table($t)->orderBy('id')->lazyById(500, 'id')->each(function ($row) use ($t, $cols, $hours) {
                $upd = [];
                foreach ($cols as $c) {
                    if (!empty($row->$c)) {
                        $upd[$c] = Carbon::parse($row->$c)->addHours($hours)->format('Y-m-d H:i:s');
                    }
                }
                if ($upd) {
                    DB::table($t)->where('id', $row->id)->update($upd);
                }
            });
        }
    }

    public function up(): void   { $this->shift(7); }
    public function down(): void { $this->shift(-7); }
};
