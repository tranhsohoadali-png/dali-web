<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Size;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Tính lại giá cho các đơn bị lỗi thu thiếu (do luồng đặt hàng cũ không gửi size_id
 * nên server rơi về $prod->price thay vì giá theo kích thước).
 *
 * AN TOÀN: chỉ chạm các đơn có mã truyền vào; tính lại y hệt logic placeOrder
 * (giá theo Size + giảm % chuyển khoản + miễn ship theo ngưỡng). Chạy nhiều lần
 * cho cùng kết quả (idempotent) vì luôn tính từ giá Size gốc.
 *
 *   php artisan dali:fix-order-price DALI-872762 DALI-884957
 *   php artisan dali:fix-order-price DALI-872762 --dry-run
 */
class FixOrderPrice extends Command
{
    protected $signature = 'dali:fix-order-price {codes* : Mã đơn cần sửa} {--dry-run : Chỉ xem trước, không ghi}';
    protected $description = 'Tính lại giá đơn theo kích thước (sửa lỗi đơn thu thiếu)';

    public function handle(): int
    {
        $dry     = (bool) $this->option('dry-run');
        $sizes   = [];
        foreach (Size::all() as $sz) $sizes[$sz->label] = (int) $sz->price;

        $discPct  = (int) (DB::table('admin_settings')->where('key', 'discount_bank')->value('value') ?? 10);
        $freeFrom = (int) (DB::table('admin_settings')->where('key', 'free_ship_from')->value('value') ?? 299000);

        foreach ($this->argument('codes') as $code) {
            $o = Order::where('code', $code)->first();
            if (!$o) { $this->error("  $code: không tìm thấy"); continue; }

            $sub = 0; $changed = [];
            $items = OrderItem::where('order_id', $o->id)->get();
            foreach ($items as $it) {
                $label = trim(explode(chr(0xC2) . chr(0xB7), (string) $it->product_size)[0]);
                if (isset($sizes[$label])) {
                    $correct = $sizes[$label];
                    if ((int) $it->price !== $correct) {
                        $changed[] = "{$it->product_size}: " . number_format($it->price) . ' → ' . number_format($correct);
                        if (!$dry) { $it->price = $correct; $it->subtotal = $correct * (int) $it->quantity; $it->save(); }
                    }
                    $sub += $correct * (int) $it->quantity;
                } else {
                    $sub += (int) $it->subtotal; // cỡ lạ (vd combo) — giữ nguyên
                }
            }

            $disc  = ($o->payment_method === 'BANK' ? (int) round($sub * $discPct / 100) : 0) + (int) $o->coupon_discount;
            $after = $sub - $disc;
            $ship  = ($after >= $freeFrom) ? 0 : (int) $o->ship_fee;
            $total = $after + $ship;

            $this->line("── {$code} ({$o->status}, {$o->payment_method}) ──");
            foreach ($changed as $c) $this->line("   • $c");
            $this->line('   tạm tính ' . number_format($o->subtotal) . ' → ' . number_format($sub)
                . ' | giảm ' . number_format($o->discount) . ' → ' . number_format($disc)
                . ' | ship ' . number_format($o->ship_fee) . ' → ' . number_format($ship)
                . ' | TỔNG ' . number_format($o->total) . ' → ' . number_format($total));

            if (!$dry) {
                $o->subtotal = $sub; $o->discount = $disc; $o->ship_fee = $ship; $o->total = $total; $o->save();
                $this->info("   ✓ đã lưu");
            }
        }

        if ($dry) $this->warn('[XEM TRƯỚC] không ghi gì vào database.');
        return self::SUCCESS;
    }
}
