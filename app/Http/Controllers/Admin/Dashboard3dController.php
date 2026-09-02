<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Don3d;
use App\Models\Sp3d;

/**
 * Tổng quan riêng cho Xưởng in 3D — chỉ đọc bảng don_3d / sp_3d,
 * KHÔNG dính số liệu site tranh. Là trang mặc định khi vào khu 3D.
 */
class Dashboard3dController extends Controller
{
    /** Trạng thái tính vào doanh thu (đã bắt đầu in trở đi; bỏ chờ cọc + huỷ). */
    private const DT_TT = ['dang_in', 'dong_goi', 'dang_giao', 'hoan_tat'];

    /** Nhãn trạng thái đơn (khớp Don3dController::TRANG_THAI). */
    private const TT = [
        'cho_coc'   => 'Chờ cọc',
        'dang_in'   => 'Đang in',
        'dong_goi'  => 'Đóng gói',
        'dang_giao' => 'Đang giao',
        'hoan_tat'  => 'Hoàn tất',
        'huy'       => 'Huỷ',
    ];

    public function index()
    {
        $today = now()->toDateString();
        $ym    = now()->format('Y-m');

        $stats = [
            'don_today'     => Don3d::whereDate('created_at', $today)->count(),
            'don_month'     => Don3d::where('created_at', 'like', $ym . '%')->count(),
            'dt_today'      => (int) Don3d::whereDate('created_at', $today)->whereIn('tt', self::DT_TT)->sum('tong'),
            'dt_month'      => (int) Don3d::where('created_at', 'like', $ym . '%')->whereIn('tt', self::DT_TT)->sum('tong'),
            'don_cho_coc'   => Don3d::where('tt', 'cho_coc')->count(),
            'don_dang_giao' => Don3d::where('tt', 'dang_giao')->count(),
            'don_hoan_tat'  => Don3d::where('tt', 'hoan_tat')->count(),
            'sp_dang_ban'   => Sp3d::where('hien', true)->count(),
            'sp_tong'       => Sp3d::count(),
        ];

        // Doanh thu + số đơn 6 tháng gần nhất
        $chart = [];
        for ($i = 5; $i >= 0; $i--) {
            $m = now()->subMonths($i);
            $k = $m->format('Y-m');
            $chart[] = [
                'label' => $m->format('m/Y'),
                'don'   => Don3d::where('created_at', 'like', $k . '%')->count(),
                'dt'    => (int) Don3d::where('created_at', 'like', $k . '%')->whereIn('tt', self::DT_TT)->sum('tong'),
            ];
        }

        $recent = Don3d::latest()->take(8)->get();
        $top    = Sp3d::where('da_ban', '>', 0)->orderByDesc('da_ban')->take(5)->get();
        $tt     = self::TT;

        return view('admin.sp3d.tong-quan', compact('stats', 'chart', 'recent', 'top', 'tt'));
    }
}
