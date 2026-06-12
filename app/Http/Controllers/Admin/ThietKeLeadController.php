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
        $q = trim((string) $r->input('q', ''));
        $leads = DesignLead::when($q, fn($w) => $w->where('phone', 'like', "%$q%"))
            ->orderByDesc('id');

        if ($r->input('xuat') === 'csv') {
            $rows = $leads->get();
            $csv = "STT,SĐT,Thời gian,Ảnh gốc,Ảnh AI,Bản đồ màu\n";
            foreach ($rows as $i => $l) {
                $csv .= ($i + 1) . ',' . $l->phone . ',' . $l->created_at->format('d/m/Y H:i') . ','
                      . '"' . $l->original_url . '","' . $l->enhanced_url . '","' . $l->result_url . '"' . "\n";
            }
            return response("\xEF\xBB\xBF" . $csv, 200, [
                'Content-Type'        => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="khach-thiet-ke-' . now()->format('Y-m-d') . '.csv"',
            ]);
        }

        return view('admin.thietke-leads', ['leads' => $leads->paginate(50)->withQueryString(), 'q' => $q]);
    }
}
