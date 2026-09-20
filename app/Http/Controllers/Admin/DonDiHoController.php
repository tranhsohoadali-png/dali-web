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
        if ($request->filled('dai_ly')) $q->where('dai_ly_id', (int) $request->dai_ly);
        if ($request->tt_tt === 'chua') $q->where('da_thanh_toan', false);
        elseif ($request->tt_tt === 'da') $q->where('da_thanh_toan', true);
        if ($request->filled('search')) $q->where(fn ($w) => $w->where('ma', 'like', '%' . $request->search . '%')
            ->orWhere('dai_ly_ten', 'like', '%' . $request->search . '%')
            ->orWhere('dai_ly_sdt', 'like', '%' . $request->search . '%'));
        $orders = $q->paginate(20)->withQueryString();
        $tt     = DonDiHo::TRANG_THAI;
        $moi    = DonDiHo::where('tt', 'moi')->count();
        return view('admin.diho.index', compact('orders', 'tt', 'moi'));
    }

    /** Đối soát đại lý: gộp tổng số đơn / số lượng / tiền sỉ theo từng đại lý (lọc theo ngày). */
    public function doiSoat(Request $request)
    {
        $tu  = $request->filled('tu')  ? $request->date('tu')->startOfDay()  : null;
        $den = $request->filled('den') ? $request->date('den')->endOfDay()   : null;
        $base = DonDiHo::query()->where('tt', '!=', 'huy');
        if ($tu)  $base->where('created_at', '>=', $tu);
        if ($den) $base->where('created_at', '<=', $den);

        $rows = (clone $base)
            ->selectRaw('dai_ly_id, dai_ly_ten, dai_ly_sdt,
                COUNT(*) as so_don, SUM(so_luong) as tong_sl, SUM(tong_si) as tong_tien,
                SUM(CASE WHEN da_thanh_toan = 0 THEN tong_si ELSE 0 END) as chua_tien,
                SUM(CASE WHEN da_thanh_toan = 0 THEN 1 ELSE 0 END) as chua_don')
            ->groupBy('dai_ly_id', 'dai_ly_ten', 'dai_ly_sdt')
            ->orderByDesc('chua_tien')->orderByDesc('tong_tien')
            ->get();

        $tong = [
            'don'  => (int) $rows->sum('so_don'),
            'sl'   => (int) $rows->sum('tong_sl'),
            'tien' => (int) $rows->sum('tong_tien'),
            'chua' => (int) $rows->sum('chua_tien'),
        ];
        return view('admin.diho.doisoat', compact('rows', 'tong', 'tu', 'den'));
    }

    /** Đảo trạng thái đã/chưa thu tiền của MỘT đơn. */
    public function danhDauTt(DonDiHo $don)
    {
        $moi = !$don->da_thanh_toan;
        $don->update(['da_thanh_toan' => $moi, 'thanh_toan_luc' => $moi ? now() : null]);
        return back()->with('ok', ($moi ? 'Đã đánh dấu ĐÃ thu tiền đơn ' : 'Đã bỏ đánh dấu thu tiền đơn ') . $don->ma);
    }

    /** Đánh dấu ĐÃ thu tiền cho TẤT CẢ đơn chưa đối soát của một đại lý (trong khoảng ngày). */
    public function danhDauDaiLy(Request $request)
    {
        $request->validate(['dai_ly_id' => 'required|integer']);
        $q = DonDiHo::where('dai_ly_id', (int) $request->dai_ly_id)->where('da_thanh_toan', false)->where('tt', '!=', 'huy');
        if ($request->filled('tu'))  $q->where('created_at', '>=', $request->date('tu')->startOfDay());
        if ($request->filled('den')) $q->where('created_at', '<=', $request->date('den')->endOfDay());
        $n = $q->update(['da_thanh_toan' => true, 'thanh_toan_luc' => now()]);
        return back()->with('ok', "Đã đánh dấu {$n} đơn của đại lý là ĐÃ thu tiền.");
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

    /** Tải ảnh ghi chú của một dòng (VD danh sách môn của khách) — đĩa private, chỉ admin. */
    public function taiAnhMon(DonDiHo $don, int $idx)
    {
        $line = ($don->chi_tiet ?: [])[$idx] ?? null;
        $rel  = $line['anh_ghi_chu'] ?? null;
        if (!$rel || !Storage::disk('local')->exists($rel)) abort(404, 'Không tìm thấy ảnh.');
        $ext = strtolower(pathinfo($rel, PATHINFO_EXTENSION) ?: 'jpg');
        return Storage::disk('local')->download($rel, 'monanh-' . $don->ma . '-' . $idx . '.' . $ext);
    }

    public function destroy(DonDiHo $don)
    {
        $ma = $don->ma;
        if ($don->nhan_vc_path) Storage::disk('local')->delete($don->nhan_vc_path);
        // Xoá cả ảnh ghi chú từng dòng (nếu có)
        foreach (($don->chi_tiet ?: []) as $l) {
            if (!empty($l['anh_ghi_chu'])) Storage::disk('local')->delete($l['anh_ghi_chu']);
        }
        $don->delete();
        return redirect()->route('admin.diho.index')->with('ok', 'Đã xoá đơn ' . $ma);
    }
}
