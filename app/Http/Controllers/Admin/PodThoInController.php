<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PodThoIn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/** Cổng POD: tài khoản thợ in (chỉ vào được /tho-in, không vào admin). */
class PodThoInController extends Controller
{
    public function index()
    {
        $items = PodThoIn::orderByDesc('created_at')->get();
        return view('admin.pod.thoin', compact('items'));
    }

    public function store(Request $request)
    {
        $v = $request->validate([
            'ten'     => 'required|string|max:120',
            'sdt'     => 'required|string|max:20',
            'matkhau' => 'required|string|min:6|max:100',
        ]);
        $sdt = preg_replace('/[^0-9+]/', '', $v['sdt']);
        if (PodThoIn::where('sdt', $sdt)->exists()) {
            return back()->withErrors(['sdt' => 'Số điện thoại này đã có thợ in.'])->withInput();
        }
        PodThoIn::create(['ten' => $v['ten'], 'sdt' => $sdt, 'matkhau' => Hash::make($v['matkhau']), 'hien' => true]);
        return back()->with('ok', 'Đã tạo tài khoản thợ in "' . $v['ten'] . '". Gửi thợ link ' . url('/tho-in') . ', số điện thoại và mật khẩu.');
    }

    public function update(Request $request, PodThoIn $tho)
    {
        $v = $request->validate([
            'ten'     => 'required|string|max:120',
            'matkhau' => 'nullable|string|min:6|max:100',
        ]);
        $data = ['ten' => $v['ten']];
        if (!empty($v['matkhau'])) $data['matkhau'] = Hash::make($v['matkhau']);
        $tho->update($data);
        return back()->with('ok', 'Đã cập nhật thợ in.');
    }

    /** Khoá / mở. Khoá có hiệu lực ngay ở lần bấm kế tiếp của thợ (ThoInAuth kiểm mỗi lần). */
    public function khoa(PodThoIn $tho)
    {
        $tho->update(['hien' => !$tho->hien]);
        return back()->with('ok', ($tho->hien ? 'Đã mở' : 'Đã khoá') . ' thợ in "' . $tho->ten . '".');
    }
}
