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
        // Đơn đăng ký chờ duyệt lên đầu
        $items = PodSeller::with('nhanVien')->orderByDesc('cho_duyet')->orderByDesc('created_at')->get();
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
        if (PodSeller::sdtDaDung($sdt)) {
            return back()->withErrors(['sdt' => 'Số điện thoại này đã có seller hoặc là tài khoản nhân viên của seller.'])->withInput();
        }
        $s = PodSeller::create([
            'ma' => PodSeller::taoMa(), 'ten' => $v['ten'], 'sdt' => $sdt,
            'matkhau' => Hash::make($v['matkhau']), 'ghi_chu' => $v['ghi_chu'] ?? null, 'hien' => true,
        ]);
        return back()->with('ok', 'Đã tạo seller "' . $s->ten . '" — mã ' . $s->ma . '. Gửi seller link ' . config('pod.url') . '/cong, số điện thoại và mật khẩu.');
    }

    /** Duyệt seller tự đăng ký: mở tài khoản, seller đăng nhập bằng mật khẩu họ đã đặt khi đăng ký. */
    public function duyet(PodSeller $seller)
    {
        if (!$seller->cho_duyet) return back()->withErrors(['seller' => 'Seller này không ở trạng thái chờ duyệt.']);
        $seller->update(['cho_duyet' => false, 'hien' => true]);
        return back()->with('ok', 'Đã duyệt "' . $seller->ten . '" (mã ' . $seller->ma . '). Báo seller qua Zalo ' . $seller->sdt . ' đăng nhập ở ' . config('pod.url') . '/cong.');
    }

    /** Từ chối đơn đăng ký: xoá hẳn (chưa có ví, đơn hay mẫu nào). */
    public function tuChoi(PodSeller $seller)
    {
        if (!$seller->cho_duyet) return back()->withErrors(['seller' => 'Chỉ xoá được đơn đăng ký đang chờ duyệt.']);
        $ten = $seller->ten;
        $seller->delete();
        return back()->with('ok', 'Đã từ chối và xoá đơn đăng ký "' . $ten . '".');
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
        if (PodSeller::sdtDaDung($sdt, $seller->id)) {
            return back()->withErrors(['sdt' => 'Số điện thoại này đã có seller khác hoặc là tài khoản nhân viên.'])->withInput();
        }
        $data = ['ten' => $v['ten'], 'sdt' => $sdt, 'ghi_chu' => $v['ghi_chu'] ?? null];
        $doiMk = !empty($v['matkhau']);
        if ($doiMk) $data['matkhau'] = Hash::make($v['matkhau']);
        $seller->update($data);
        if ($doiMk) PodPhien::where('seller_id', $seller->id)->whereNull('nhan_vien_id')->delete();   // chỉ máy của chủ shop; nhân viên giữ phiên
        return back()->with('ok', 'Đã cập nhật seller.' . ($doiMk ? ' Đã đăng xuất mọi máy của chủ shop (đổi mật khẩu). Tài khoản nhân viên của shop vẫn đăng nhập — chủ shop khoá ở mục Nhân viên nếu cần.' : ''));
    }

    /**
     * Bật / tắt ví trả sau (KHÔNG giới hạn): bật thì mọi đơn vào hàng đợi in ngay, ví âm thành nợ, seller chuyển khoản
     * "NAP <mã>" sau để trả (bấm Nạp tiền như thường). Bật thì đơn Chờ nạp tiền chạy luôn; tắt thì đơn mới phải đủ tiền.
     */
    public function traSau(Request $request, PodSeller $seller)
    {
        $v = $request->validate(['tra_sau' => 'required|in:0,1']);
        $seller->update(['tra_sau' => $v['tra_sau'] === '1']);
        $n = PodSo::xuLyChoNap($seller);
        $no = -min(0, $seller->soDu());
        $tien = fn ($x) => number_format($x, 0, ',', '.') . 'đ';
        return back()->with('ok', ($seller->tra_sau
                ? 'Đã bật trả sau cho ' . $seller->ten . ' (không giới hạn): đơn vào máy in ngay, ví âm thành nợ.'
                : 'Đã tắt trả sau của ' . $seller->ten . ' — từ giờ phải nạp trước.'
                    . ($no > 0 ? ' Seller đang nợ ' . $tien($no) . ': đơn mới chờ nạp tiền cho tới khi trả hết nợ.' : ''))
            . ($n > 0 ? " $n đơn chờ nạp tiền đã vào hàng đợi in." : ''));
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
        $request->merge(['so_tien' => self::boCham($request->input('so_tien'))]);
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

    /** Ô tiền gõ có dấu chấm nghìn ("10.000.000", "-50.000") -> chuỗi số cho validate. Không có chữ số thì để nguyên (validate báo lỗi). */
    private static function boCham($x): ?string
    {
        $x = trim((string) $x);
        if ($x === '') return null;
        $so = preg_replace('/\D/', '', $x);
        return $so === '' ? $x : (preg_match('/^[-\x{2212}]/u', $x) ? '-' : '') . $so;
    }

    public function vi(PodSeller $seller)
    {
        $dong = PodVi::where('seller_id', $seller->id)->orderByDesc('id')->paginate(100);
        $soDu = $seller->soDu();
        return view('admin.pod.seller-vi', compact('seller', 'dong', 'soDu'));
    }
}
