<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Cài đặt khu Xưởng in 3D: bảng giá bảng TKB / phiếu, tài khoản nhận tiền,
 * danh sách môn học. Đây là nguồn giá cho cỗ máy checkout (Api3dController).
 */
class CauHinh3dController extends Controller
{
    /** Các mức giá + nhãn để dựng form. */
    public const GIA_NHAN = [
        'full'          => 'Bộ bảng TKB đầy đủ (bảng + đủ phiếu)',
        'board'         => 'Chỉ tấm bảng nền',
        'phieu'         => '1 phiếu môn in sẵn (mua lẻ)',
        'custom'        => '1 phiếu đặt tên riêng',
        'chan'          => 'Chân bảng — 1 chiếc',
        'boPhieu'       => 'Bộ 20 phiếu môn (cho khách đã có bảng)',
        'shipPhieu'     => 'Phí gửi đơn chỉ có phiếu',
        'freeshipPhieu' => 'Miễn phí gửi phiếu khi đơn từ mức này',
        'minPhieu'      => 'Đơn phiếu lẻ tối thiểu (nhắc khách mua từ)',
    ];

    public function index()
    {
        $gia  = $this->get('GIA', []);
        $bank = $this->get('BANK', ['bin' => '', 'stk' => '', 'ten' => '']);
        $mon  = $this->get('MON', []);
        $giaNhan = self::GIA_NHAN;
        return view('admin.cauhinh3d.index', compact('gia', 'bank', 'mon', 'giaNhan'));
    }

    public function update(Request $request)
    {
        // Bảng giá — số nguyên không âm
        $gia = [];
        foreach (array_keys(self::GIA_NHAN) as $k) {
            $gia[$k] = max(0, (int) preg_replace('/\D/', '', (string) $request->input("gia.$k", 0)));
        }
        $this->set('GIA', $gia);

        // Tài khoản nhận tiền
        $this->set('BANK', [
            'bin' => strtoupper(trim((string) $request->input('bank.bin', ''))),
            'stk' => preg_replace('/\s+/', '', (string) $request->input('bank.stk', '')),
            'ten' => strtoupper(trim((string) $request->input('bank.ten', ''))),
        ]);

        // Danh sách môn: mảng ten[] + mau[] song song, bỏ dòng trống
        $tens = $request->input('mon_ten', []);
        $maus = $request->input('mon_mau', []);
        $mon  = [];
        foreach ($tens as $i => $ten) {
            $ten = trim((string) $ten);
            if ($ten === '') continue;
            $mon[] = [$ten, (string) ($maus[$i] ?? '#8CC63E')];
        }
        $this->set('MON', $mon);

        return back()->with('ok', 'Đã lưu cài đặt Xưởng in 3D. Giá mới áp dụng ngay cho web khách.');
    }

    /* ---------- helpers ---------- */

    private function get(string $key, $fallback)
    {
        $row = DB::table('cau_hinh_3d')->where('khoa', $key)->value('gia_tri');
        if ($row === null) return $fallback;
        $v = json_decode($row, true);
        return $v === null ? $fallback : $v;
    }

    private function set(string $key, $value): void
    {
        DB::table('cau_hinh_3d')->updateOrInsert(
            ['khoa' => $key],
            ['gia_tri' => json_encode($value, JSON_UNESCAPED_UNICODE), 'updated_at' => now(), 'created_at' => now()],
        );
    }
}
