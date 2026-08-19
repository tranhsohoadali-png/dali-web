<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Don3d;
use App\Models\Sp3d;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
        $moi = $request->tt;
        $cu  = $don->tt;
        $upd = ['tt' => $moi];
        if ($moi === 'hoan_tat') $upd['xong_luc'] = now();
        $don->update($upd);

        // Lượt bán "thật" — giống sold_count tranhdali.vn: cộng khi đơn VÀO 'hoàn tất',
        // trừ lại nếu rời khỏi 'hoàn tất' (đổi nhầm/huỷ) để không đếm sai.
        if ($moi === 'hoan_tat' && $cu !== 'hoan_tat')      $this->capNhatDaBan($don, +1);
        elseif ($cu === 'hoan_tat' && $moi !== 'hoan_tat')  $this->capNhatDaBan($don, -1);

        return back()->with('ok', 'Đã đổi trạng thái đơn ' . $don->ma . ' → ' . self::TRANG_THAI[$moi]);
    }

    public function markPaid(Don3d $don)
    {
        $don->update(['trang_thai_tt' => 'da_nhan_coc']);
        return back()->with('ok', 'Đã xác nhận nhận cọc ' . number_format((int) $don->tra_ngay, 0, ',', '.') . 'đ cho đơn ' . $don->ma);
    }

    public function destroy(Don3d $don)
    {
        $ma = $don->ma;
        if ($don->tt === 'hoan_tat') $this->capNhatDaBan($don, -1); // gỡ lượt bán đã cộng
        $don->delete();
        return redirect()->route('admin.don3d.index')->with('ok', 'Đã xoá đơn ' . $ma);
    }

    /**
     * Cộng/trừ lượt bán thật vào sp_3d.da_ban theo từng dòng trong đơn.
     * Dòng `product:{slug}` -> đúng sản phẩm; `tkb:*` -> bang-tkb; `subject:*` (phiếu) bỏ qua.
     * $dau = +1 khi đơn hoàn tất, -1 khi rời khỏi hoàn tất / xoá.
     */
    private function capNhatDaBan(Don3d $don, int $dau): void
    {
        foreach (($don->chi_tiet ?: []) as $l) {
            $sku = (string) ($l['sku'] ?? '');
            $qty = (int) ($l['qty'] ?? 0);
            if ($qty < 1) continue;
            $slug = null;
            if (Str::startsWith($sku, 'product:'))  $slug = substr($sku, 8);
            elseif (Str::startsWith($sku, 'tkb:'))  $slug = 'bang-tkb';
            if (!$slug) continue;
            $sp = Sp3d::where('slug', $slug)->first();
            if (!$sp) continue;
            if ($dau > 0) $sp->increment('da_ban', $qty);
            else          $sp->decrement('da_ban', min($qty, (int) $sp->da_ban)); // không cho âm
        }
    }
}
