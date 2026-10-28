<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PodBienThe;
use App\Models\PodDon;
use App\Models\PodSeller;
use App\Services\PodSo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/** Cổng POD: hàng đợi in + đơn seller. Mặc định chỉ hiện đơn cần làm (Chờ in, Đang in). */
class PodDonController extends Controller
{
    public function index(Request $request)
    {
        $tt = (string) $request->query('tt', 'can_lam');
        $q = PodDon::with(['dong', 'seller'])->orderBy('id');
        if ($tt === 'can_lam') $q->whereIn('trang_thai', ['cho_in', 'dang_in']);
        elseif (isset(PodDon::TRANG_THAI[$tt])) $q->where('trang_thai', $tt)->reorder()->orderByDesc('id');
        else $q->reorder()->orderByDesc('id');
        $items = $q->limit(500)->get();
        $dem = PodDon::selectRaw('trang_thai, count(*) n')->groupBy('trang_thai')->pluck('n', 'trang_thai');
        return view('admin.pod.don-index', compact('items', 'tt', 'dem'));
    }

    public function show(PodDon $don)
    {
        $don->load(['dong', 'seller']);
        $fileIn = PodBienThe::whereIn('id', $don->dong->pluck('bien_the_id'))->pluck('file_in', 'id');
        $coNhan = (bool) PodSo::duongNhan($don);
        return view('admin.pod.don-show', compact('don', 'fileIn', 'coNhan'));
    }

    /** Chờ in -> Đang in -> Đã giao (kèm mã vận đơn). Không lùi trạng thái; huỷ dùng nút riêng. */
    public function trangThai(Request $request, PodDon $don)
    {
        $v = $request->validate([
            'trang_thai' => 'required|in:dang_in,da_giao',
            'ma_van_don' => 'nullable|string|max:60',
        ]);
        $duoc = ['dang_in' => ['cho_in'], 'da_giao' => ['cho_in', 'dang_in']];
        if (!in_array($don->trang_thai, $duoc[$v['trang_thai']], true)) {
            return back()->withErrors(['trang_thai' => 'Đơn đang ở "' . $don->tenTrangThai() . '", không chuyển được.']);
        }
        $data = ['trang_thai' => $v['trang_thai']];
        if ($v['trang_thai'] === 'da_giao') {
            $data['giao_luc'] = now();
            $data['ma_van_don'] = trim((string) ($v['ma_van_don'] ?? '')) ?: $don->ma_van_don;
        }
        $don->update($data);
        return back()->with('ok', 'Đơn ' . $don->ma . ': ' . $don->tenTrangThai() . '.');
    }

    public function huy(PodDon $don)
    {
        if (!PodSo::huyDon($don, 'admin')) return back()->withErrors(['trang_thai' => 'Đơn đã giao hoặc đã huỷ.']);
        return back()->with('ok', 'Đã huỷ đơn ' . $don->ma . ($don->da_tru ? ', đã hoàn tiền vào ví seller.' : '.'));
    }

    public function nhan(PodDon $don)
    {
        $rel = PodSo::duongNhan($don);
        if (!$rel) abort(404);
        return Storage::disk('local')->download($rel, 'nhan-' . $don->ma . '.' . pathinfo($rel, PATHINFO_EXTENSION));
    }
}
