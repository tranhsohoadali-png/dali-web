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
        $items = DaiLy::orderByDesc('created_at')->get();
        return view('admin.daily.index', compact('items'));
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
        $dai_ly->update($data);
        return back()->with('ok', 'Đã cập nhật đại lý.');
    }

    /** Khoá/mở đại lý. Khoá thì xoá token phiên (đăng xuất ngay). */
    public function toggle(DaiLy $dai_ly)
    {
        $moKhoa = !$dai_ly->hien;
        $dai_ly->update(['hien' => $moKhoa, 'token' => $moKhoa ? $dai_ly->token : null]);
        return back()->with('ok', $moKhoa ? 'Đã mở lại đại lý.' : 'Đã khoá đại lý (đăng xuất khỏi web).');
    }

    public function destroy(DaiLy $dai_ly)
    {
        $ten = $dai_ly->ten;
        $dai_ly->delete();
        return back()->with('ok', 'Đã xoá đại lý "' . $ten . '".');
    }
}
