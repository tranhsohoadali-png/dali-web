<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DaiLy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/** Quản lý tài khoản đại lý (đăng nhập web 3D để xem giá sỉ). */
class DaiLyController extends Controller
{
    public function index()
    {
        // Tự đăng ký (chờ duyệt) lên đầu
        $items = DaiLy::orderByDesc('cho_duyet')->orderByDesc('created_at')->get();
        $affIds = $items->pluck('affiliate_id')->merge($items->pluck('gioi_thieu_aff_id'))->filter()->unique();
        $affs = \App\Models\Affiliate::whereIn('id', $affIds)->get()->keyBy('id');
        $choDuyet = $items->where('cho_duyet', true)->count();
        return view('admin.daily.index', compact('items', 'affs', 'choDuyet'));
    }

    public function store(Request $request)
    {
        $v = $request->validate([
            'ten'     => 'required|string|max:120',
            'sdt'     => 'required|string|max:20',
            'matkhau' => 'required|string|min:4|max:100',
            'ghi_chu' => 'nullable|string|max:500',
        ]);
        $sdt = preg_replace('/[^0-9+]/', '', $v['sdt']);
        if (DaiLy::where('sdt', $sdt)->exists()) {
            return back()->withErrors(['sdt' => 'Số điện thoại này đã có đại lý.'])->withInput();
        }
        DaiLy::create([
            'ten' => $v['ten'], 'sdt' => $sdt,
            'matkhau' => Hash::make($v['matkhau']),
            'ghi_chu' => $v['ghi_chu'] ?? null, 'hien' => true,
            'sll_luon' => $request->boolean('sll_luon'),
        ]);
        return back()->with('ok', 'Đã thêm đại lý "' . $v['ten'] . '".');
    }

    public function update(Request $request, DaiLy $dai_ly)
    {
        $v = $request->validate([
            'ten'     => 'required|string|max:120',
            'sdt'     => 'required|string|max:20',
            'matkhau' => 'nullable|string|min:4|max:100',
            'ghi_chu' => 'nullable|string|max:500',
        ]);
        $sdt = preg_replace('/[^0-9+]/', '', $v['sdt']);
        if (DaiLy::where('sdt', $sdt)->where('id', '!=', $dai_ly->id)->exists()) {
            return back()->withErrors(['sdt' => 'Số điện thoại này đã có đại lý khác.'])->withInput();
        }
        $data = ['ten' => $v['ten'], 'sdt' => $sdt, 'ghi_chu' => $v['ghi_chu'] ?? null, 'sll_luon' => $request->boolean('sll_luon')];
        if (!empty($v['matkhau'])) { $data['matkhau'] = Hash::make($v['matkhau']); $data['token'] = null; }

        // Người giới thiệu (mã giới thiệu). Để trống = không có. Chỉ ảnh hưởng hoa hồng các đơn thu tiền SAU này.
        if ($request->has('ma_gt')) {
            $ma = strtoupper(trim((string) $request->input('ma_gt', '')));
            if ($ma === '') {
                $data['gioi_thieu_aff_id'] = null;
            } else {
                $aff = \App\Models\Affiliate::where('code', $ma)->first();
                if (!$aff) return back()->withErrors(['ma_gt' => 'Không tìm thấy mã giới thiệu "' . $ma . '".'])->withInput();
                if ($dai_ly->affiliate_id && (int) $dai_ly->affiliate_id === (int) $aff->id) {
                    return back()->withErrors(['ma_gt' => 'Đại lý không thể tự giới thiệu chính mình.'])->withInput();
                }
                $data['gioi_thieu_aff_id'] = $aff->id;
            }
        }
        $dai_ly->update($data);
        return back()->with('ok', 'Đã cập nhật đại lý.');
    }

    /** Khoá/mở đại lý. Khoá thì xoá token phiên (đăng xuất ngay). */
    public function toggle(DaiLy $dai_ly)
    {
        $moKhoa = !$dai_ly->hien;
        $duyet  = $moKhoa && $dai_ly->cho_duyet;
        $upd = ['hien' => $moKhoa, 'token' => $moKhoa ? $dai_ly->token : null];
        if ($moKhoa) $upd['cho_duyet'] = false; // mở = duyệt luôn đăng ký tự gửi
        $dai_ly->update($upd);
        if ($duyet) return back()->with('ok', 'Đã DUYỆT đại lý "' . $dai_ly->ten . '" — nhắn Zalo ' . $dai_ly->sdt . ' báo đăng nhập được rồi.');
        return back()->with('ok', $moKhoa ? 'Đã mở lại đại lý.' : 'Đã khoá đại lý (đăng xuất khỏi web).');
    }

    public function destroy(DaiLy $dai_ly)
    {
        $ten = $dai_ly->ten;
        $dai_ly->delete();
        return back()->with('ok', 'Đã xoá đại lý "' . $ten . '".');
    }
}
