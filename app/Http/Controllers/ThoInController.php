<?php
namespace App\Http\Controllers;

use App\Models\PodBienThe;
use App\Models\PodDon;
use App\Models\PodThoIn;
use App\Services\PodSo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

/**
 * Trang thợ in (/tho-in): CHỈ hàng đợi in POD.
 * Thợ in thấy những gì cần để in và gửi (mẫu, phân loại, màu, chữ, file in, nhãn, nơi giao),
 * KHÔNG thấy tiền, ví seller, kho mẫu, nhật ký, và không huỷ được đơn.
 * Đơn Chờ nạp tiền (chưa trả) và Đã huỷ coi như không tồn tại với thợ in.
 */
class ThoInController extends Controller
{
    private const THAY_DUOC = ['cho_in', 'dang_in', 'da_giao'];

    public function dangNhapForm()
    {
        if (session('tho_in_id')) return redirect()->route('tho-in.hang-doi');
        return response()->view('tho-in.dang-nhap')->header('X-Robots-Tag', 'noindex, nofollow');
    }

    public function dangNhap(Request $request)
    {
        $v = $request->validate([
            'sdt'     => 'required|string|max:20',
            'matkhau' => 'required|string|max:100',
        ]);
        $sdt = preg_replace('/[^0-9+]/', '', $v['sdt']);
        $t = PodThoIn::where('sdt', $sdt)->first();
        if (!$t || !Hash::check($v['matkhau'], $t->matkhau)) {
            return back()->withErrors(['sdt' => 'Sai số điện thoại hoặc mật khẩu.'])->withInput(['sdt' => $v['sdt']]);
        }
        if (!$t->hien) return back()->withErrors(['sdt' => 'Tài khoản đang khoá, hỏi chủ xưởng.'])->withInput(['sdt' => $v['sdt']]);
        $request->session()->regenerate();
        session(['tho_in_id' => $t->id]);
        $t->update(['dang_nhap_luc' => now()]);
        return redirect()->route('tho-in.hang-doi');
    }

    public function dangXuat(Request $request)
    {
        session()->forget('tho_in_id');
        $request->session()->regenerateToken();
        return redirect()->route('tho-in.dang-nhap');
    }

    public function hangDoi(Request $request)
    {
        $canLam = PodDon::with('dong')->whereIn('trang_thai', ['cho_in', 'dang_in'])->orderBy('id')->limit(300)->get();
        $daGiao = PodDon::with('dong')->where('trang_thai', 'da_giao')->where('giao_luc', '>=', now()->subDays(7))
            ->orderByDesc('giao_luc')->limit(100)->get();
        $tho = $request->attributes->get('tho_in');
        return response()->view('tho-in.hang-doi', compact('canLam', 'daGiao', 'tho'))->header('X-Robots-Tag', 'noindex, nofollow');
    }

    public function don(Request $request, PodDon $don)
    {
        $this->chiDonThayDuoc($don);
        $don->load('dong');
        $fileIn = PodBienThe::whereIn('id', $don->dong->pluck('bien_the_id'))->pluck('file_in', 'id');
        $coNhan = (bool) PodSo::duongNhan($don);
        $tho = $request->attributes->get('tho_in');
        return response()->view('tho-in.don', compact('don', 'fileIn', 'coNhan', 'tho'))->header('X-Robots-Tag', 'noindex, nofollow');
    }

    public function trangThai(Request $request, PodDon $don)
    {
        $this->chiDonThayDuoc($don);
        $v = $request->validate([
            'trang_thai' => 'required|in:dang_in,da_giao',
            'ma_van_don' => 'nullable|string|max:60',
        ]);
        $tho = $request->attributes->get('tho_in');
        $loi = PodSo::chuyenTrangThai($don, $v['trang_thai'], $v['ma_van_don'] ?? null, $tho->ten);
        if ($loi) return back()->withErrors(['trang_thai' => $loi]);
        return redirect()->route('tho-in.hang-doi')->with('ok', 'Đơn ' . $don->ma . ': ' . $don->tenTrangThai() . '.');
    }

    public function nhan(PodDon $don)
    {
        $this->chiDonThayDuoc($don);
        $rel = PodSo::duongNhan($don);
        if (!$rel) abort(404);
        return Storage::disk('local')->download($rel, 'nhan-' . $don->ma . '.' . pathinfo($rel, PATHINFO_EXTENSION));
    }

    private function chiDonThayDuoc(PodDon $don): void
    {
        if (!in_array($don->trang_thai, self::THAY_DUOC, true)) abort(404);
    }
}
