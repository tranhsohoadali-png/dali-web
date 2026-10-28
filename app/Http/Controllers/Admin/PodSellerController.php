<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PodDon;
use App\Models\PodPhien;
use App\Models\PodSeller;
use App\Models\PodVi;
use App\Services\PodSo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/** Cổng POD: tài khoản seller + ví (nạp tiền, điều chỉnh, sổ ví). Xưởng cấp tài khoản. */
class PodSellerController extends Controller
{
    public function index()
    {
        $items = PodSeller::orderByDesc('created_at')->get();
        $soDu = DB::table('pod_vi')->selectRaw('seller_id, sum(so_tien) t')->groupBy('seller_id')->pluck('t', 'seller_id');
        $choNap = PodDon::where('trang_thai', 'cho_nap')->selectRaw('seller_id, count(*) n, sum(tong_tien) t')->groupBy('seller_id')->get()->keyBy('seller_id');
        return view('admin.pod.seller', compact('items', 'soDu', 'choNap'));
    }

    public function store(Request $request)
    {
        $v = $request->validate([
            'ten'     => 'required|string|max:120',
            'sdt'     => 'required|string|max:20',
            'matkhau' => 'required|string|min:6|max:100',
            'ghi_chu' => 'nullable|string|max:500',
        ]);
        $sdt = preg_replace('/[^0-9+]/', '', $v['sdt']);
        if (PodSeller::where('sdt', $sdt)->exists()) {
            return back()->withErrors(['sdt' => 'Số điện thoại này đã có seller.'])->withInput();
        }
        $s = PodSeller::create([
            'ma' => PodSeller::taoMa(), 'ten' => $v['ten'], 'sdt' => $sdt,
            'matkhau' => Hash::make($v['matkhau']), 'ghi_chu' => $v['ghi_chu'] ?? null, 'hien' => true,
        ]);
        return back()->with('ok', 'Đã tạo seller "' . $s->ten . '" — mã ' . $s->ma . '. Gửi seller link /pod, số điện thoại và mật khẩu.');
    }

    public function update(Request $request, PodSeller $seller)
    {
        $v = $request->validate([
            'ten'     => 'required|string|max:120',
            'sdt'     => 'required|string|max:20',
            'matkhau' => 'nullable|string|min:6|max:100',
            'ghi_chu' => 'nullable|string|max:500',
        ]);
        $sdt = preg_replace('/[^0-9+]/', '', $v['sdt']);
        if (PodSeller::where('sdt', $sdt)->where('id', '!=', $seller->id)->exists()) {
            return back()->withErrors(['sdt' => 'Số điện thoại này đã có seller khác.'])->withInput();
        }
        $data = ['ten' => $v['ten'], 'sdt' => $sdt, 'ghi_chu' => $v['ghi_chu'] ?? null];
        $doiMk = !empty($v['matkhau']);
        if ($doiMk) $data['matkhau'] = Hash::make($v['matkhau']);
        $seller->update($data);
        if ($doiMk) PodPhien::where('seller_id', $seller->id)->delete();
        return back()->with('ok', 'Đã cập nhật seller.' . ($doiMk ? ' Đã đăng xuất mọi thiết bị (đổi mật khẩu).' : ''));
    }

    /** Khoá / mở. Khoá thì đăng xuất mọi thiết bị ngay; mẫu riêng vẫn giữ, không chuyển cho ai. */
    public function khoa(PodSeller $seller)
    {
        $seller->update(['hien' => !$seller->hien]);
        if (!$seller->hien) PodPhien::where('seller_id', $seller->id)->delete();
        return back()->with('ok', ($seller->hien ? 'Đã mở' : 'Đã khoá') . ' seller "' . $seller->ten . '".');
    }

    /** Nạp tiền (đã thấy tiền về) hoặc điều chỉnh (+/−). Nạp xong tự đẩy đơn Chờ nạp tiền vào sản xuất. */
    public function nap(Request $request, PodSeller $seller)
    {
        $v = $request->validate([
            'so_tien' => 'required|integer|not_in:0|min:-100000000|max:100000000',
            'loai'    => 'required|in:nap,dieu_chinh',
            'ghi_chu' => 'nullable|string|max:300',
        ]);
        if ($v['loai'] === 'nap' && $v['so_tien'] < 0) {
            return back()->withErrors(['so_tien' => 'Nạp tiền phải là số dương. Muốn trừ thì chọn Điều chỉnh.']);
        }
        if ($v['loai'] === 'dieu_chinh' && trim((string) ($v['ghi_chu'] ?? '')) === '') {
            return back()->withErrors(['ghi_chu' => 'Điều chỉnh phải ghi lý do.']);
        }
        $n = PodSo::napTien($seller, (int) $v['so_tien'], $v['loai'], $v['ghi_chu'] ?? null);
        return back()->with('ok', 'Đã ghi ' . number_format($v['so_tien'], 0, ',', '.') . 'đ vào ví ' . $seller->ten . '.'
            . ($n > 0 ? " $n đơn chờ nạp tiền đã vào hàng đợi in." : ''));
    }

    public function vi(PodSeller $seller)
    {
        $dong = PodVi::where('seller_id', $seller->id)->orderByDesc('id')->paginate(100);
        $soDu = $seller->soDu();
        return view('admin.pod.seller-vi', compact('seller', 'dong', 'soDu'));
    }
}
