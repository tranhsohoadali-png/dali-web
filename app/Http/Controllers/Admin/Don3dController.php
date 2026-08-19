<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Don3d;
use Illuminate\Http\Request;

/** Xem & xử lý đơn hàng khu Xưởng in 3D. Màn riêng, bảng don_3d. */
class Don3dController extends Controller
{
    public const TRANG_THAI = [
        'cho_coc'   => 'Chờ cọc',
        'dang_in'   => 'Đang in',
        'dong_goi'  => 'Đóng gói',
        'dang_giao' => 'Đang giao',
        'hoan_tat'  => 'Hoàn tất',
        'huy'       => 'Huỷ',
    ];

    public function index(Request $request)
    {
        $q = Don3d::orderByDesc('created_at');
        if ($request->filled('tt'))     $q->where('tt', $request->tt);
        if ($request->filled('search')) $q->where(fn ($w) => $w->where('ma', 'like', '%' . $request->search . '%')
            ->orWhere('ten', 'like', '%' . $request->search . '%')
            ->orWhere('sdt', 'like', '%' . $request->search . '%'));
        $orders  = $q->paginate(20)->withQueryString();
        $tt      = self::TRANG_THAI;
        $choCoc  = Don3d::where('tt', 'cho_coc')->count();
        return view('admin.don3d.index', compact('orders', 'tt', 'choCoc'));
    }

    public function show(Don3d $don)
    {
        $tt = self::TRANG_THAI;
        return view('admin.don3d.show', compact('don', 'tt'));
    }

    public function updateStatus(Request $request, Don3d $don)
    {
        $request->validate(['tt' => 'required|in:' . implode(',', array_keys(self::TRANG_THAI))]);
        $upd = ['tt' => $request->tt];
        if ($request->tt === 'hoan_tat') $upd['xong_luc'] = now();
        $don->update($upd);
        return back()->with('ok', 'Đã đổi trạng thái đơn ' . $don->ma . ' → ' . self::TRANG_THAI[$request->tt]);
    }

    public function markPaid(Don3d $don)
    {
        $don->update(['trang_thai_tt' => 'da_nhan_coc']);
        return back()->with('ok', 'Đã xác nhận nhận cọc ' . number_format((int) $don->tra_ngay, 0, ',', '.') . 'đ cho đơn ' . $don->ma);
    }

    public function destroy(Don3d $don)
    {
        $ma = $don->ma;
        $don->delete();
        return redirect()->route('admin.don3d.index')->with('ok', 'Đã xoá đơn ' . $ma);
    }
}
