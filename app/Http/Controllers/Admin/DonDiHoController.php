<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DonDiHo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/** Đơn "đi đơn hộ" của đại lý (đơn TMĐT). Xem, đổi trạng thái, tải nhãn vận chuyển. */
class DonDiHoController extends Controller
{
    public function index(Request $request)
    {
        $q = DonDiHo::orderByDesc('created_at');
        if ($request->filled('tt'))     $q->where('tt', $request->tt);
        if ($request->filled('search')) $q->where(fn ($w) => $w->where('ma', 'like', '%' . $request->search . '%')
            ->orWhere('dai_ly_ten', 'like', '%' . $request->search . '%')
            ->orWhere('dai_ly_sdt', 'like', '%' . $request->search . '%'));
        $orders = $q->paginate(20)->withQueryString();
        $tt     = DonDiHo::TRANG_THAI;
        $moi    = DonDiHo::where('tt', 'moi')->count();
        return view('admin.diho.index', compact('orders', 'tt', 'moi'));
    }

    public function show(DonDiHo $don)
    {
        $tt = DonDiHo::TRANG_THAI;
        return view('admin.diho.show', compact('don', 'tt'));
    }

    public function updateStatus(Request $request, DonDiHo $don)
    {
        $request->validate([
            'tt'    => 'required|in:' . implode(',', array_keys(DonDiHo::TRANG_THAI)),
            'ma_vc' => 'nullable|string|max:60',
            'vc'    => 'nullable|string|max:40',
        ]);
        $upd = ['tt' => $request->tt];
        if ($request->filled('ma_vc')) $upd['ma_vc'] = trim($request->ma_vc);
        if ($request->filled('vc'))    $upd['vc']    = trim($request->vc);
        if ($request->tt === 'da_gui') $upd['gui_luc'] = now();
        $don->update($upd);
        return back()->with('ok', 'Đã cập nhật đơn ' . $don->ma . ' → ' . (DonDiHo::TRANG_THAI[$request->tt] ?? $request->tt));
    }

    /** Tải nhãn/hoá đơn vận chuyển (đĩa private) — chỉ admin đã đăng nhập. */
    public function taiNhan(DonDiHo $don)
    {
        if (!$don->nhan_vc_path || !Storage::disk('local')->exists($don->nhan_vc_path)) {
            abort(404, 'Không tìm thấy file nhãn.');
        }
        $ext = strtolower(pathinfo($don->nhan_vc_path, PATHINFO_EXTENSION) ?: 'dat');
        return Storage::disk('local')->download($don->nhan_vc_path, 'nhan-' . $don->ma . '.' . $ext);
    }

    public function destroy(DonDiHo $don)
    {
        $ma = $don->ma;
        if ($don->nhan_vc_path) Storage::disk('local')->delete($don->nhan_vc_path);
        $don->delete();
        return redirect()->route('admin.diho.index')->with('ok', 'Đã xoá đơn ' . $ma);
    }
}
