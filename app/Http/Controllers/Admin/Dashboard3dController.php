<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Don3d;
use App\Models\Sp3d;
use Illuminate\Support\Facades\DB;

/**
 * Tổng quan riêng cho Xưởng in 3D — chỉ đọc bảng don_3d / sp_3d / su_kien_3d,
 * KHÔNG dính số liệu site tranh. Là trang mặc định khi vào khu 3D.
 */
class Dashboard3dController extends Controller
{
    /** Trạng thái tính vào doanh thu (đã bắt đầu in trở đi; bỏ chờ cọc + huỷ). */
    private const DT_TT = ['dang_in', 'dong_goi', 'dang_giao', 'hoan_tat'];

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
        $hasEv = \Illuminate\Support\Facades\Schema::hasTable('su_kien_3d');

        // Đếm sự kiện web 3D theo loại + phạm vi thời gian
        $ev = function (string $name, ?string $scope = null) use ($hasEv, $today, $ym) {
            if (!$hasEv) return 0;
            $q = DB::table('su_kien_3d')->where('event', $name);
            if ($scope === 'today') $q->whereDate('created_at', $today);
            if ($scope === 'month') $q->where('created_at', 'like', $ym . '%');
            return (int) $q->count();
        };

        $stats = [
            'don_today'      => Don3d::whereDate('created_at', $today)->count(),
            'don_month'      => Don3d::where('created_at', 'like', $ym . '%')->count(),
            'dt_today'       => (int) Don3d::whereDate('created_at', $today)->whereIn('tt', self::DT_TT)->sum('tong'),
            'dt_month'       => (int) Don3d::where('created_at', 'like', $ym . '%')->whereIn('tt', self::DT_TT)->sum('tong'),
            'don_cho_coc'    => Don3d::where('tt', 'cho_coc')->count(),
            'don_dang_giao'  => Don3d::where('tt', 'dang_giao')->count(),
            'don_hoan_tat'   => Don3d::where('tt', 'hoan_tat')->count(),
            'sp_dang_ban'    => Sp3d::where('hien', true)->count(),
            'sp_tong'        => Sp3d::count(),
            // Hoạt động khách trên web 3D
            'view_today'     => $ev('view_product', 'today'),
            'view_total'     => $ev('view_product'),
            'cart_month'     => $ev('add_cart', 'month'),
            'cart_total'     => $ev('add_cart'),
            'checkout_month' => $ev('begin_checkout', 'month'),
            'checkout_total' => $ev('begin_checkout'),
        ];

        // Khách theo tỉnh/thành (từ đơn 3D)
        $province = Don3d::select('tinh', DB::raw('COUNT(*) as don'), DB::raw('SUM(tong) as dt'))
            ->whereNotNull('tinh')->where('tinh', '!=', '')
            ->groupBy('tinh')->orderByDesc('don')->take(10)->get();
        $province_total = (int) Don3d::whereNotNull('tinh')->where('tinh', '!=', '')->count();

        // Doanh thu + số đơn 7 ngày
        $chart_7day = [];
        for ($i = 6; $i >= 0; $i--) {
            $d = now()->subDays($i)->toDateString();
            $chart_7day[] = [
                'date' => now()->subDays($i)->format('d/m'),
                'don'  => Don3d::whereDate('created_at', $d)->count(),
                'dt'   => (int) Don3d::whereDate('created_at', $d)->whereIn('tt', self::DT_TT)->sum('tong'),
            ];
        }

        // Doanh thu 12 tháng
        $chart_12month = [];
        for ($i = 11; $i >= 0; $i--) {
            $m = now()->subMonths($i);
            $k = $m->format('Y-m');
            $chart_12month[] = [
                'label' => $m->format('m/Y'),
                'don'   => Don3d::where('created_at', 'like', $k . '%')->count(),
                'dt'    => (int) Don3d::where('created_at', 'like', $k . '%')->whereIn('tt', self::DT_TT)->sum('tong'),
            ];
        }

        // Hoạt động web 3D 7 ngày (lượt xem SP + thêm giỏ)
        $act_7day = [];
        for ($i = 6; $i >= 0; $i--) {
            $d = now()->subDays($i)->toDateString();
            $act_7day[] = [
                'date'  => now()->subDays($i)->format('d/m'),
                'views' => $hasEv ? (int) DB::table('su_kien_3d')->where('event', 'view_product')->whereDate('created_at', $d)->count() : 0,
                'carts' => $hasEv ? (int) DB::table('su_kien_3d')->where('event', 'add_cart')->whereDate('created_at', $d)->count() : 0,
            ];
        }

        $recent = Don3d::latest()->take(8)->get();
        $top    = Sp3d::where('da_ban', '>', 0)->orderByDesc('da_ban')->take(5)->get();
        $tt     = self::TT;

        return view('admin.sp3d.tong-quan', compact(
            'stats', 'province', 'province_total',
            'chart_7day', 'chart_12month', 'act_7day', 'recent', 'top', 'tt'
        ));
    }
}
