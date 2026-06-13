<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DesignLead;
use Illuminate\Http\Request;

/** Admin → Khách thiết kế: danh sách SĐT khách đã lưu bản thiết kế + xuất CSV. */
class ThietKeLeadController extends Controller
{
    public function index(Request $r)
    {
        $q       = trim((string) $r->input('q', ''));
        $chuaDat = $r->input('chuadat') === '1';

        // SĐT đã từng đặt đơn thiết kế -> để lọc nhóm "chưa đặt".
        $datPhones = \App\Models\Order::where('note', 'like', 'ĐƠN THIẾT KẾ%')
            ->pluck('customer_phone')->map(fn($p) => preg_replace('/\D/', '', (string) $p))->unique()->all();

        $leads = DesignLead::when($q, fn($w) => $w->where('phone', 'like', "%$q%"))
            ->when($chuaDat, fn($w) => $w->whereNotIn('phone', $datPhones))
            ->orderByDesc('id');

        if ($r->input('xuat') === 'csv') {
            $rows = $leads->get();
            $csv = "STT,SĐT,Đã đặt?,Thời gian,Ảnh gốc,Ảnh AI,Bản đồ màu\n";
            foreach ($rows as $i => $l) {
                $daDat = in_array(preg_replace('/\D/', '', $l->phone), $datPhones) ? 'Đã đặt' : 'CHƯA đặt';
                $csv .= ($i + 1) . ',' . $l->phone . ',' . $daDat . ',' . $l->created_at->format('d/m/Y H:i') . ','
                      . '"' . $l->original_url . '","' . $l->enhanced_url . '","' . $l->result_url . '"' . "\n";
            }
            return response("\xEF\xBB\xBF" . $csv, 200, [
                'Content-Type'        => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="khach-thiet-ke-' . now()->format('Y-m-d') . '.csv"',
            ]);
        }

        $totalAll  = DesignLead::count();
        $totalChua = DesignLead::whereNotIn('phone', $datPhones)->count();

        return view('admin.thietke-leads', [
            'leads'     => $leads->paginate(50)->withQueryString(),
            'q'         => $q,
            'chuaDat'   => $chuaDat,
            'datPhones' => $datPhones,
            'totalAll'  => $totalAll,
            'totalChua' => $totalChua,
        ]);
    }
}
