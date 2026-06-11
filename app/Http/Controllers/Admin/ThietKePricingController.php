<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Bảng giá trang "Thiết kế theo yêu cầu" (/thiet-ke):
 * ma trận Kích thước × Số màu, lưu JSON trong admin_settings (thietke_pricing).
 * Admin sửa trực tiếp từng ô (bấm vào ô -> gõ -> tự lưu).
 */
class ThietKePricingController extends Controller
{
    /** Bảng giá mặc định (giá thật của shop; 48/54/60 màu nội suy — sửa được trong admin). */
    public static function defaults(): array
    {
        return [
            'colors' => [24, 30, 36, 42, 48, 54, 60],
            'sizes'  => [
                ['label' => '30x40 cm', 'note' => 'Đã gồm Cấp 1', 'prices' => [370000, 390000, 415000, 460000, 505000, 555000, 610000]],
                ['label' => '40x50 cm', 'note' => 'Đã gồm Cấp 1', 'prices' => [450000, 470000, 495000, 540000, 585000, 635000, 690000]],
                ['label' => '40x65 cm', 'note' => 'Đã gồm Cấp 1', 'prices' => [490000, 510000, 535000, 580000, 625000, 675000, 730000]],
                ['label' => '50x65 cm', 'note' => 'Đã gồm Cấp 1', 'prices' => [560000, 580000, 605000, 650000, 695000, 745000, 800000]],
            ],
        ];
    }

    /** Bảng giá hiện hành: đọc từ settings, hỏng/trống thì dùng mặc định. */
    public static function current(): array
    {
        $raw = DB::table('admin_settings')->where('key', 'thietke_pricing')->value('value');
        $p   = is_string($raw) ? json_decode($raw, true) : null;
        if (!is_array($p) || empty($p['colors']) || empty($p['sizes'])) {
            return self::defaults();
        }
        return $p;
    }

    public function index()
    {
        $pricing = self::current();
        return view('admin.thietke-pricing', compact('pricing'));
    }

    /** Lưu cả ma trận (AJAX, gọi mỗi khi sửa xong 1 ô). */
    public function save(Request $r)
    {
        $p = json_decode((string) $r->input('pricing'), true);
        if (!is_array($p) || empty($p['colors']) || !is_array($p['colors']) || empty($p['sizes']) || !is_array($p['sizes'])) {
            return response()->json(['ok' => false, 'msg' => 'Dữ liệu bảng giá không hợp lệ.'], 422);
        }
        $colors = array_values(array_map(fn($c) => max(1, (int) $c), $p['colors']));
        $sizes  = [];
        foreach ($p['sizes'] as $s) {
            if (!is_array($s)) continue;
            $prices = array_values(array_map(fn($v) => max(0, (int) $v), (array) ($s['prices'] ?? [])));
            // số ô giá phải khớp số cột màu
            $prices = array_pad(array_slice($prices, 0, count($colors)), count($colors), 0);
            $sizes[] = [
                'label'  => trim((string) ($s['label'] ?? '')) ?: 'Kích thước',
                'note'   => trim((string) ($s['note'] ?? '')),
                'prices' => $prices,
            ];
        }
        if (!$sizes) {
            return response()->json(['ok' => false, 'msg' => 'Cần ít nhất 1 dòng kích thước.'], 422);
        }
        DB::table('admin_settings')->updateOrInsert(
            ['key' => 'thietke_pricing'],
            ['value' => json_encode(['colors' => $colors, 'sizes' => $sizes], JSON_UNESCAPED_UNICODE), 'updated_at' => now()]
        );
        return response()->json(['ok' => true]);
    }
}
