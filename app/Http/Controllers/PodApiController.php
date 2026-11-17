<?php
namespace App\Http\Controllers;

use App\Models\PodDon;
use App\Models\PodMau;
use App\Models\PodNhanVien;
use App\Models\PodNoiGiao;
use App\Models\PodPhien;
use App\Models\PodSeller;
use App\Models\PodVi;
use App\Services\PodSo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

/**
 * API cổng seller POD (/api/pod/*), trang cổng ở /pod cùng tên miền nên không cần CORS.
 * Đăng nhập bằng header X-Pod-Token (mỗi thiết bị một phiên, chỉ lưu băm).
 *
 * GIỮ KÍN MẪU ĐỘC QUYỀN: mọi chỗ lấy mẫu đều qua PodMau::hienCho($seller->id).
 * Mẫu không thuộc quyền trả 404 y như mã không tồn tại — không phải 403.
 */
class PodApiController extends Controller
{
    /** Phiên hiện tại + nhân viên đang đăng nhập (null = chủ shop), đặt bởi sellerTuToken(). */
    private ?PodPhien $phien = null;
    private ?PodNhanVien $nv = null;

    /**
     * Seller (shop) từ token, hoặc null. Phiên của NHÂN VIÊN trả về shop của nhân viên đó và đặt $this->nv;
     * nhân viên bị chủ shop khoá/xoá, hoặc shop bị xưởng khoá -> null (mất phiên).
     */
    private function sellerTuToken(Request $r): ?PodSeller
    {
        $this->phien = null; $this->nv = null;
        $token = trim((string) $r->header('X-Pod-Token', ''));
        if (strlen($token) < 20) return null;
        $p = PodPhien::where('token_hash', PodPhien::bam($token))->first();
        if (!$p) return null;
        if ($p->dung_luc && $p->dung_luc->lt(now()->subDays(PodPhien::HET_HAN_NGAY))) { $p->delete(); return null; }
        $s = PodSeller::whereKey($p->seller_id)->where('hien', true)->first();
        if (!$s) return null;
        if ($p->nhan_vien_id) {
            $nv = PodNhanVien::whereKey($p->nhan_vien_id)->where('seller_id', $s->id)->where('hien', true)->first();
            if (!$nv) return null;
            $this->nv = $nv;
        }
        if (!$p->dung_luc || $p->dung_luc->lt(now()->subMinutes(10))) {
            PodPhien::whereKey($p->id)->update(['dung_luc' => now(), 'ip' => $r->ip()]);
        }
        $this->phien = $p;
        return $s;
    }

    /** Seller đã đăng nhập VÀ (người đang dùng) đã đồng ý điều khoản; không thì dừng với 401/403. */
    private function seller(Request $r): PodSeller
    {
        $s = $this->sellerTuToken($r);
        if (!$s) abort(response()->json(['ok' => false, 'error' => 'Phiên đăng nhập đã hết, vui lòng đăng nhập lại.'], 401));
        $dongY = $this->nv ? $this->nv->dong_y_luc : $s->dong_y_luc;
        if (!$dongY) abort(response()->json(['ok' => false, 'error' => 'Cần đồng ý điều khoản bảo mật trước.', 'can_dong_y' => true], 403));
        return $s;
    }

    /** Như seller() nhưng CHỈ CHỦ SHOP (ví, hồ sơ shop, nhân viên, xoá nơi giao). Nhân viên -> 403. */
    private function chuShop(Request $r): PodSeller
    {
        $s = $this->seller($r);
        if ($this->nv) abort(response()->json(['ok' => false, 'error' => 'Chỉ chủ shop dùng được mục này.'], 403));
        return $s;
    }

    /** Phiên của CHÍNH người đang đăng nhập (chủ shop: phiên không gắn nhân viên; nhân viên: phiên của riêng họ). */
    private function phienCuaToi(PodSeller $s)
    {
        return PodPhien::where('seller_id', $s->id)
            ->when($this->nv, fn ($q) => $q->where('nhan_vien_id', $this->nv->id), fn ($q) => $q->whereNull('nhan_vien_id'));
    }

    /* ============ Đăng nhập ============ */

    /** POST /api/pod/dang-nhap {sdt, matkhau} -> {ok, token} */
    public function dangNhap(Request $request)
    {
        $v = $request->validate([
            'sdt'     => 'required|string|max:20',
            'matkhau' => 'required|string|max:100',
        ]);
        $sdt = preg_replace('/[^0-9+]/', '', $v['sdt']);
        // Chặn dò mật khẩu: đếm lần SAI theo từng SĐT (8 lần/5 phút) và theo IP (30 lần/5 phút). Đăng nhập đúng chỉ xoá
        // bộ đếm của CHÍNH SĐT đó — trước đây xoá bộ đếm IP nên dò xen kẽ với tài khoản thật của mình là không bao giờ bị chặn.
        $kSdt = 'pod-login:sdt:' . $sdt;
        $kIp  = 'pod-login:ip:' . $request->ip();
        if (RateLimiter::tooManyAttempts($kSdt, 8) || RateLimiter::tooManyAttempts($kIp, 30)) {
            return response()->json(['ok' => false, 'error' => 'Thử lại sau ít phút.'], 429);
        }
        $s = PodSeller::where('sdt', $sdt)->first();
        $nv = null;
        if (!$s) {   // không phải chủ shop -> thử tài khoản nhân viên (số điện thoại không trùng giữa hai bảng)
            $nv = PodNhanVien::where('sdt', $sdt)->first();
            $s = $nv ? PodSeller::find($nv->seller_id) : null;
        }
        $hash = $nv ? $nv->matkhau : ($s ? $s->matkhau : null);
        if (!$s || !$hash || !Hash::check($v['matkhau'], $hash)) {
            RateLimiter::hit($kSdt, 300);
            RateLimiter::hit($kIp, 300);
            return response()->json(['ok' => false, 'error' => 'Sai số điện thoại hoặc mật khẩu.'], 401);
        }
        if (!$s->hien) { // chỉ nói lý do khi đã đúng mật khẩu
            return response()->json(['ok' => false, 'error' => $s->cho_duyet
                ? 'Tài khoản đang chờ xưởng duyệt. Xưởng sẽ báo qua Zalo khi duyệt xong.'
                : 'Tài khoản đang tạm khoá, vui lòng liên hệ xưởng.'], 403);
        }
        if ($nv && !$nv->hien) {
            return response()->json(['ok' => false, 'error' => 'Tài khoản nhân viên đang bị chủ shop khoá.'], 403);
        }
        $token = Str::random(48);
        PodPhien::create([
            'seller_id'    => $s->id,
            'nhan_vien_id' => $nv?->id,
            'token_hash'   => PodPhien::bam($token),
            'thiet_bi'     => mb_substr((string) $request->userAgent(), 0, 120),
            'ip'           => $request->ip(),
            'dung_luc'     => now(),
        ]);
        // Tối đa TOI_DA máy cho MỖI người (chủ shop và từng nhân viên tính riêng): bỏ phiên cũ nhất
        $cu = PodPhien::where('seller_id', $s->id)
            ->when($nv, fn ($q) => $q->where('nhan_vien_id', $nv->id), fn ($q) => $q->whereNull('nhan_vien_id'))
            ->orderByDesc('dung_luc')->orderByDesc('id')->skip(PodPhien::TOI_DA)->take(1000)->pluck('id');
        if ($cu->isNotEmpty()) PodPhien::whereIn('id', $cu)->delete();
        ($nv ?: $s)->update(['dang_nhap_luc' => now()]);
        RateLimiter::clear($kSdt);
        return response()->json(['ok' => true, 'token' => $token]);
    }

    /** GET /api/pod/toi -> thông tin seller (cả khi chưa đồng ý điều khoản). */
    public function toi(Request $request)
    {
        $s = $this->sellerTuToken($request);
        if (!$s) return response()->json(['ok' => false], 401);
        $nv = $this->nv;
        return response()->json([
            'ok'        => true,
            'ten'       => $s->ten,
            'ma'        => $s->ma,
            'vai_tro'   => $nv ? 'nhan_vien' : 'chu',
            'nhan_vien' => $nv ? ['id' => $nv->id, 'ten' => $nv->ten] : null,
            'dong_y'    => (bool) ($nv ? $nv->dong_y_luc : $s->dong_y_luc),
            // nhân viên KHÔNG thấy tiền trong ví của shop
            'so_du'     => $nv ? null : $s->soDu(),
            'tra_sau'   => $nv ? null : $s->traSau(),  // ví trả sau: đơn luôn vào máy in, ví âm thành nợ (không giới hạn)
            'gia'       => PodSo::bangGia(),   // đ/gram theo chất liệu, vd {"PETG":900,"PLA":1300}
        ]);
    }

    /** POST /api/pod/dong-y — người đang đăng nhập đồng ý điều khoản bảo mật (một lần; nhân viên đồng ý riêng). */
    public function dongY(Request $request)
    {
        $s = $this->sellerTuToken($request);
        if (!$s) return response()->json(['ok' => false], 401);
        $ai = $this->nv ?: $s;
        if (!$ai->dong_y_luc) $ai->update(['dong_y_luc' => now()]);
        return response()->json(['ok' => true]);
    }

    /** POST /api/pod/dang-xuat — chỉ thiết bị này. */
    public function dangXuat(Request $request)
    {
        $token = trim((string) $request->header('X-Pod-Token', ''));
        if (strlen($token) >= 20) PodPhien::where('token_hash', PodPhien::bam($token))->delete();
        return response()->json(['ok' => true]);
    }

    /* ============ Hồ sơ + bảo mật ============ */

    /** GET /api/pod/ho-so -> hồ sơ shop (nhân viên chỉ xem) + người đang đăng nhập. */
    public function hoSo(Request $request)
    {
        $s = $this->seller($request);
        $nv = $this->nv;
        return response()->json([
            'ok'         => true,
            'ma'         => $s->ma,
            'ten'        => $s->ten,
            'sdt'        => $s->sdt,
            'san_ban'    => $s->san_ban,
            'link_shop'  => $s->link_shop,
            'tham_gia'   => optional($s->created_at)->format('d/m/Y'),
            'so_don'     => PodDon::where('seller_id', $s->id)->count(),
            'vai_tro'    => $nv ? 'nhan_vien' : 'chu',
            'nhan_vien'  => $nv ? ['ten' => $nv->ten, 'sdt' => $nv->sdt] : null,
            'doi_mk_luc' => optional(($nv ?: $s)->doi_mk_luc)->format('d/m/Y H:i'),
        ]);
    }

    /** POST /api/pod/ho-so {ten, san_ban?, link_shop?} — chỉ chủ shop. Đổi SĐT đăng nhập vẫn qua xưởng. */
    public function hoSoLuu(Request $request)
    {
        $s = $this->chuShop($request);
        $v = $request->validate([
            'ten'       => 'required|string|max:120',
            'san_ban'   => 'nullable|string|max:120',
            'link_shop' => 'nullable|string|max:300|url:http,https',
        ], ['ten.required' => 'Nhập tên shop.', 'link_shop.url' => 'Link shop phải bắt đầu bằng https://']);
        $s->update(['ten' => trim($v['ten']), 'san_ban' => $v['san_ban'] ?? null, 'link_shop' => $v['link_shop'] ?? null]);
        return response()->json(['ok' => true, 'ten' => $s->ten]);
    }

    /**
     * POST /api/pod/doi-mat-khau {cu, moi, moi_confirmation} — người đang đăng nhập đổi mật khẩu CỦA MÌNH.
     * Đúng thì đăng xuất mọi máy khác của chính người đó (giữ máy đang dùng). Sai mật khẩu cũ 5 lần / 10 phút thì chặn.
     */
    public function doiMatKhau(Request $request)
    {
        $s = $this->seller($request);
        $ai = $this->nv ?: $s;
        $v = $request->validate([
            'cu'  => 'required|string|max:100',
            'moi' => 'required|string|min:6|max:100|confirmed|different:cu',
        ], [
            'moi.min'       => 'Mật khẩu mới tối thiểu 6 ký tự.',
            'moi.confirmed' => 'Hai lần nhập mật khẩu mới chưa giống nhau.',
            'moi.different' => 'Mật khẩu mới phải khác mật khẩu hiện tại.',
        ]);
        $key = 'pod-doimk:' . $this->phien->id;
        if (RateLimiter::tooManyAttempts($key, 5)) {
            return response()->json(['ok' => false, 'error' => 'Nhập sai nhiều lần, thử lại sau ít phút.'], 429);
        }
        if (!Hash::check($v['cu'], $ai->matkhau)) {
            RateLimiter::hit($key, 600);
            return response()->json(['ok' => false, 'error' => 'Mật khẩu hiện tại không đúng.'], 422);
        }
        RateLimiter::clear($key);
        $ai->update(['matkhau' => Hash::make($v['moi']), 'doi_mk_luc' => now()]);
        $so = $this->phienCuaToi($s)->where('id', '!=', $this->phien->id)->delete();
        return response()->json(['ok' => true, 'dang_xuat' => $so]);
    }

    /** GET /api/pod/phien -> các máy đang đăng nhập CỦA CHÍNH người này (không thấy máy của người khác). */
    public function phien(Request $request)
    {
        $s = $this->seller($request);
        $ds = $this->phienCuaToi($s)->orderByDesc('dung_luc')->orderByDesc('id')->get();
        return response()->json(['ok' => true, 'phien' => $ds->map(fn ($p) => [
            'id'       => $p->id,
            'thiet_bi' => PodPhien::nhanThietBi($p->thiet_bi),
            'ip'       => $p->ip,
            'dung_luc' => optional($p->dung_luc)->format('d/m/Y H:i'),
            'tu_luc'   => optional($p->created_at)->format('d/m/Y H:i'),
            'hien_tai' => $p->id === $this->phien->id,
        ])->all()]);
    }

    /** DELETE /api/pod/phien/{id} — đăng xuất một máy của chính mình. */
    public function phienXoa(Request $request, int $id)
    {
        $s = $this->seller($request);
        $this->phienCuaToi($s)->whereKey($id)->delete();
        return response()->json(['ok' => true]);
    }

    /** POST /api/pod/phien/dang-xuat-khac — đăng xuất mọi máy khác của chính mình. */
    public function phienDangXuatKhac(Request $request)
    {
        $s = $this->seller($request);
        $so = $this->phienCuaToi($s)->where('id', '!=', $this->phien->id)->delete();
        return response()->json(['ok' => true, 'dang_xuat' => $so]);
    }

    /* ============ Nhân viên (chỉ chủ shop) ============ */

    /** GET /api/pod/nhan-vien */
    public function nhanVien(Request $request)
    {
        $s = $this->chuShop($request);
        $ds = PodNhanVien::where('seller_id', $s->id)->conDung()->orderBy('id')->get();
        $soDon = PodDon::where('seller_id', $s->id)->whereNotNull('nhan_vien_id')->selectRaw('nhan_vien_id, count(*) n')->groupBy('nhan_vien_id')->pluck('n', 'nhan_vien_id');
        $soMay = PodPhien::where('seller_id', $s->id)->whereNotNull('nhan_vien_id')->selectRaw('nhan_vien_id, count(*) n')->groupBy('nhan_vien_id')->pluck('n', 'nhan_vien_id');
        return response()->json(['ok' => true, 'toi_da' => PodNhanVien::TOI_DA, 'nhan_vien' => $ds->map(fn ($n) => [
            'id'        => $n->id,
            'ten'       => $n->ten,
            'sdt'       => $n->sdt,
            'hien'      => $n->hien,
            'dang_nhap' => optional($n->dang_nhap_luc)->format('d/m/Y H:i'),
            'so_don'    => (int) ($soDon[$n->id] ?? 0),
            'so_may'    => (int) ($soMay[$n->id] ?? 0),
        ])->all()]);
    }

    /**
     * Thao tác cấp quyền (tạo nhân viên, đặt lại mật khẩu nhân viên) phải NHẬP LẠI mật khẩu chủ shop:
     * ai chỉ cầm được phiên của chủ (máy dùng chung) không lập được tài khoản "cửa hậu" sống qua lần đổi mật khẩu.
     */
    private function kiemMkChu(Request $r, PodSeller $s): ?\Illuminate\Http\JsonResponse
    {
        $key = 'pod-mkchu:' . $s->id;
        if (RateLimiter::tooManyAttempts($key, 5)) {
            return response()->json(['ok' => false, 'error' => 'Nhập sai mật khẩu nhiều lần, thử lại sau ít phút.'], 429);
        }
        $mk = $r->input('mk_chu');
        if (!is_string($mk) || $mk === '' || !Hash::check($mk, $s->matkhau)) {
            RateLimiter::hit($key, 600);
            return response()->json(['ok' => false, 'error' => 'Nhập đúng mật khẩu của chủ shop để xác nhận.'], 422);
        }
        RateLimiter::clear($key);
        return null;
    }

    /** POST /api/pod/nhan-vien {ten, sdt, matkhau, mk_chu} — tạo tài khoản nhân viên (SĐT chưa ai dùng làm tên đăng nhập). */
    public function nhanVienTao(Request $request)
    {
        $s = $this->chuShop($request);
        $v = $request->validate([
            'ten'     => 'required|string|max:120',
            'sdt'     => 'required|string|max:20',
            'matkhau' => 'required|string|min:6|max:100',
        ], ['matkhau.min' => 'Mật khẩu tối thiểu 6 ký tự.']);
        // mỗi shop 10 lần tạo / giờ: ô này trả lời "số đã có tài khoản chưa" nên không để thành máy dò số điện thoại
        $kTao = 'pod-nv-tao:' . $s->id;
        if (RateLimiter::tooManyAttempts($kTao, 10)) return response()->json(['ok' => false, 'error' => 'Tạo quá nhiều lần, thử lại sau.'], 429);
        if ($loi = $this->kiemMkChu($request, $s)) return $loi;
        RateLimiter::hit($kTao, 3600);
        $sdt = preg_replace('/[^0-9+]/', '', $v['sdt']);
        if (strlen($sdt) < 9) return response()->json(['ok' => false, 'error' => 'Số điện thoại chưa đúng.'], 422);
        if (PodNhanVien::where('seller_id', $s->id)->conDung()->count() >= PodNhanVien::TOI_DA) {
            return response()->json(['ok' => false, 'error' => 'Tối đa ' . PodNhanVien::TOI_DA . ' nhân viên.'], 422);
        }
        if (PodSeller::sdtDaDung($sdt)) {
            return response()->json(['ok' => false, 'error' => 'Số điện thoại này đã là tài khoản trên cổng, dùng số khác.'], 422);
        }
        $n = PodNhanVien::create(['seller_id' => $s->id, 'ten' => trim($v['ten']), 'sdt' => $sdt, 'matkhau' => Hash::make($v['matkhau']), 'hien' => true]);
        return response()->json(['ok' => true, 'id' => $n->id]);
    }

    /**
     * POST /api/pod/nhan-vien/{id} {ten?, matkhau? + mk_chu, hien?} — sửa tên / đặt lại mật khẩu / khoá-mở.
     * Khoá hoặc đặt lại mật khẩu thì nhân viên bị đăng xuất khỏi mọi máy. Đặt lại mật khẩu cần mật khẩu chủ shop.
     */
    public function nhanVienSua(Request $request, int $id)
    {
        $s = $this->chuShop($request);
        $n = PodNhanVien::where('seller_id', $s->id)->conDung()->whereKey($id)->first();
        if (!$n) return response()->json(['ok' => false, 'error' => 'Không tìm thấy nhân viên.'], 404);
        $v = $request->validate([
            'ten'     => 'sometimes|required|string|max:120',
            'matkhau' => 'sometimes|required|string|min:6|max:100',
            'hien'    => 'sometimes|boolean',
        ], ['matkhau.min' => 'Mật khẩu tối thiểu 6 ký tự.']);
        if (isset($v['matkhau']) && ($loi = $this->kiemMkChu($request, $s))) return $loi;
        $data = [];
        if (isset($v['ten'])) $data['ten'] = trim($v['ten']);
        if (isset($v['matkhau'])) { $data['matkhau'] = Hash::make($v['matkhau']); $data['doi_mk_luc'] = now(); }
        if (array_key_exists('hien', $v)) $data['hien'] = (bool) $v['hien'];
        $n->update($data);
        if (isset($v['matkhau']) || (array_key_exists('hien', $v) && !$v['hien'])) {
            PodPhien::where('seller_id', $s->id)->where('nhan_vien_id', $n->id)->delete();
        }
        return response()->json(['ok' => true]);
    }

    /**
     * DELETE /api/pod/nhan-vien/{id} — chưa đặt đơn nào thì xoá hẳn; đã có đơn thì giữ dòng (tên trên đơn cũ) nhưng
     * khoá + GIẢI PHÓNG số điện thoại (sdt = "#<id>") và không còn tính vào giới hạn TOI_DA.
     */
    public function nhanVienXoa(Request $request, int $id)
    {
        $s = $this->chuShop($request);
        $n = PodNhanVien::where('seller_id', $s->id)->conDung()->whereKey($id)->first();
        if (!$n) return response()->json(['ok' => false, 'error' => 'Không tìm thấy nhân viên.'], 404);
        PodPhien::where('seller_id', $s->id)->where('nhan_vien_id', $n->id)->delete();
        if (PodDon::where('nhan_vien_id', $n->id)->exists()) {
            $n->update(['hien' => false, 'sdt' => '#' . $n->id]);
            return response()->json(['ok' => true, 'da_xoa' => true, 'giu_ten' => true]);
        }
        $n->delete();
        return response()->json(['ok' => true, 'da_xoa' => true]);
    }

    /* ============ Kho mẫu ============ */

    /** GET /api/pod/mau?kho=chung|rieng -> mẫu seller được thấy. */
    public function mau(Request $request)
    {
        $s = $this->seller($request);
        $q = PodMau::hienCho($s->id)->with('bienThe')->orderByDesc('id');
        $kho = $request->query('kho');
        if ($kho === 'chung') $q->whereNull('chu_seller_id');
        if ($kho === 'rieng') $q->whereNotNull('chu_seller_id');
        $gia = PodSo::bangGia();
        return response()->json(['ok' => true, 'mau' => $q->get()->map(fn ($m) => $this->mauJson($m, $s, $gia))->all()]);
    }

    /** GET /api/pod/mau/{ma} -> 1 mẫu, hoặc 404 nếu không có HOẶC không thuộc quyền. */
    public function mauChiTiet(Request $request, string $ma)
    {
        $s = $this->seller($request);
        $m = PodMau::hienCho($s->id)->with('bienThe')->where('ma', $ma)->first();
        if (!$m) return response()->json(['ok' => false, 'error' => 'Không tìm thấy mẫu.'], 404);
        return response()->json(['ok' => true, 'mau' => $this->mauJson($m, $s, PodSo::bangGia(), true)]);
    }

    /**
     * @param array<string,int> $gia đ/gram theo chất liệu (PodSo::bangGia())
     * @param bool $chiTiet kèm dữ liệu ảnh xem trước (khung SVG khá nặng nên danh sách chỉ báo co_xem_truoc)
     */
    private function mauJson(PodMau $m, PodSeller $s, array $gia, bool $chiTiet = false): array
    {
        $anh = [];
        foreach (array_values((array) ($m->anh ?: [])) as $i => $rel) {
            $anh[] = URL::temporarySignedRoute('pod.anh', now()->addMinutes(10), ['ma' => $m->ma, 'i' => $i, 's' => $s->id], absolute: false);
        }
        return [
            'ma'          => $m->ma,
            'ten'         => $m->ten,
            'nhom'        => $m->nhom,
            'mo_ta'       => $m->mo_ta,
            'rieng'       => $m->laRieng(),
            'chia_se'     => $m->laRieng() && $m->duocCap()->exists(), // mẫu riêng cấp cho nhiều seller: cổng không được hứa "chỉ bạn thấy"; không lộ tên ai
            'anh'         => $anh,
            'phi_ca_nhan' => (int) $m->phi_ca_nhan,
            'ten_toi_da'  => $m->tenToiDa(),          // >0 = mẫu khắc tên, bắt buộc nhập tên
            'co_xem_truoc'=> !empty($m->xem_truoc),
            'xem_truoc'   => $chiTiet ? $m->xem_truoc : null,
            'co_kich_thuoc' => $m->coKichThuoc(),
            'ten_kieu'    => (object) $m->bienThe->pluck('kieu')->filter()->unique()->mapWithKeys(fn ($k) => [$k => $m->tenKieu($k)])->all(),
            'hinh_chieu'  => $chiTiet ? $this->hinhChieu($m, $s) : null,
            'bien_the'    => $m->bienThe->map(fn ($b) => [
                'id'        => $b->id,
                'ten'       => $b->ten,
                'kieu'      => $b->kieu,
                'chat_lieu' => PodSo::chatLieu($b->chat_lieu),
                // không gửi gram: seller chỉ thấy giá (chủ xưởng 11-16 "bỏ hiển thị gam trên sản phẩm")
                'mau_sac'   => $b->dsMau(),
                'kich_thuoc'=> $b->kich_thuoc,
                'gia'       => PodSo::giaBienThe($m, $b, $gia),
            ])->all(),
        ];
    }

    /**
     * GET /pod/anh/{ma}/{i}?s=&expires=&signature= — ảnh mẫu (đĩa private).
     * Link có chữ ký, hết hạn 10 phút; vẫn KIỂM LẠI quyền seller s trên mẫu: link
     * có chữ ký đúng nhưng mẫu không thuộc s vẫn ra 404.
     */
    public function anh(Request $request, string $ma, int $i)
    {
        $s = PodSeller::whereKey((int) $request->query('s'))->where('hien', true)->first();
        $m = $s ? PodMau::hienCho($s->id)->where('ma', $ma)->first() : null;
        $rel = $m ? (array_values((array) ($m->anh ?: []))[$i] ?? null) : null;
        if (!$rel || !Storage::disk('local')->exists($rel)) abort(404);
        return response()->file(Storage::disk('local')->path($rel), [
            'Cache-Control' => 'private, max-age=600',
            'X-Robots-Tag'  => 'noindex',
        ]);
    }

    /**
     * Link có chữ ký (10 phút) tới ảnh riêng của mẫu khối: {truoc, ben} — hoặc mẫu nhiều kiểu
     * {kieu: {<khoá>: {truoc, ben, bia}}}. Rỗng nếu mẫu không có ảnh hình chiếu.
     */
    private function hinhChieu(PodMau $m, PodSeller $s): array
    {
        $link = function (?string $kieu) use ($m, $s) {
            $kq = [];
            foreach (['truoc', 'ben', 'bia'] as $mat) {
                if ($m->anhHinhChieu($mat, $kieu) === null) continue;
                $kq[$mat] = URL::temporarySignedRoute('pod.anh-kt', now()->addMinutes(10),
                    array_filter(['ma' => $m->ma, 'mat' => $mat, 'kieu' => $kieu, 's' => $s->id], fn ($v) => $v !== null), absolute: false);
            }
            return $kq;
        };
        $dsKieu = $m->kieuCoAnh();
        if (!$dsKieu) return $link(null);
        $kq = [];
        foreach ($dsKieu as $k) $kq[$k] = $link($k);
        return ['kieu' => $kq];
    }

    /** GET /api/pod/anh-kt/{ma}/{truoc|ben|bia}/{kieu?}?s=&expires=&signature= — ảnh riêng mẫu khối; kiểm quyền như anh(). */
    public function anhKt(Request $request, string $ma, string $mat, ?string $kieu = null)
    {
        $s = PodSeller::whereKey((int) $request->query('s'))->where('hien', true)->first();
        $rel = $s ? PodMau::hienCho($s->id)->where('ma', $ma)->first()?->anhHinhChieu($mat, $kieu) : null;
        if (!$rel || !Storage::disk('local')->exists($rel)) abort(404);
        return response()->file(Storage::disk('local')->path($rel), ['Cache-Control' => 'private, max-age=600', 'X-Robots-Tag' => 'noindex']);
    }

    /* ============ Nơi giao ============ */

    public function noiGiao(Request $request)
    {
        $s = $this->seller($request);
        $ds = PodNoiGiao::where('seller_id', $s->id)->orderBy('id')->get(['id', 'ten', 'dia_chi', 'sdt', 'ghi_chu']);
        $md = $ds->contains('id', $s->noi_giao_mac_dinh) ? (int) $s->noi_giao_mac_dinh : null;
        return response()->json(['ok' => true, 'mac_dinh' => $md, 'noi_giao' => $ds]);
    }

    private function quyTacNoiGiao(): array
    {
        return [
            'ten'     => 'required|string|max:120',
            'dia_chi' => 'required|string|max:300',
            'sdt'     => 'nullable|string|max:20',
            'ghi_chu' => 'nullable|string|max:300',
        ];
    }

    public function noiGiaoLuu(Request $request)
    {
        $s = $this->seller($request);
        $v = $request->validate($this->quyTacNoiGiao());
        if (PodNoiGiao::where('seller_id', $s->id)->count() >= 30) {
            return response()->json(['ok' => false, 'error' => 'Tối đa 30 nơi giao.'], 422);
        }
        $n = PodNoiGiao::create($v + ['seller_id' => $s->id]);
        // nơi giao đầu tiên thành mặc định luôn
        if (!$s->noi_giao_mac_dinh || !PodNoiGiao::where('seller_id', $s->id)->whereKey($s->noi_giao_mac_dinh)->exists()) {
            $s->update(['noi_giao_mac_dinh' => $n->id]);
        }
        return response()->json(['ok' => true, 'id' => $n->id]);
    }

    /**
     * POST /api/pod/noi-giao/{id} — sửa (CHỈ CHỦ SHOP: nhân viên sửa được thì đổi được địa chỉ nơi mặc định về nhà mình).
     * Đơn cũ không đổi (đơn chụp lại nơi giao lúc đặt).
     */
    public function noiGiaoSua(Request $request, int $id)
    {
        $s = $this->chuShop($request);
        $n = PodNoiGiao::where('seller_id', $s->id)->whereKey($id)->first();
        if (!$n) return response()->json(['ok' => false, 'error' => 'Không tìm thấy nơi giao.'], 404);
        $n->update($request->validate($this->quyTacNoiGiao()));
        return response()->json(['ok' => true]);
    }

    /** POST /api/pod/noi-giao/{id}/mac-dinh — chọn sẵn khi soạn đơn (chỉ chủ shop). */
    public function noiGiaoMacDinh(Request $request, int $id)
    {
        $s = $this->chuShop($request);
        if (!PodNoiGiao::where('seller_id', $s->id)->whereKey($id)->exists()) {
            return response()->json(['ok' => false, 'error' => 'Không tìm thấy nơi giao.'], 404);
        }
        $s->update(['noi_giao_mac_dinh' => $id]);
        return response()->json(['ok' => true]);
    }

    /** DELETE — chỉ chủ shop xoá. */
    public function noiGiaoXoa(Request $request, int $id)
    {
        $s = $this->chuShop($request);
        PodNoiGiao::where('seller_id', $s->id)->whereKey($id)->delete();
        if ((int) $s->noi_giao_mac_dinh === $id) $s->update(['noi_giao_mac_dinh' => null]);
        return response()->json(['ok' => true]);
    }

    /* ============ Đơn ============ */

    /**
     * POST /api/pod/don (JSON hoặc multipart): dong[] {bien_the_id, so_luong, mau_sac?, chu?},
     * noi_giao_id, ma_don_san?, ghi_chu?, nhan? (file). Giá tính ở server, không nhận số từ trình duyệt.
     */
    public function donTao(Request $request)
    {
        $s = $this->seller($request);
        $dong = $request->input('dong');
        if (is_string($dong)) $dong = json_decode($dong, true);
        $request->merge(['dong' => is_array($dong) ? $dong : []]);
        $v = $request->validate([
            'dong'        => 'required|array|min:1|max:50',
            'noi_giao_id' => 'required|integer',
            'ma_don_san'  => 'nullable|string|max:60',
            'ghi_chu'     => 'nullable|string|max:1000',
            'nhan'        => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf|max:10240',
        ]);
        $ng = PodNoiGiao::where('seller_id', $s->id)->whereKey($v['noi_giao_id'])->first();
        if (!$ng) return response()->json(['ok' => false, 'error' => 'Chọn nơi giao.'], 422);
        $don = PodSo::datDon(
            $s, $v['dong'],
            ['ten' => $ng->ten, 'dia_chi' => $ng->dia_chi, 'sdt' => $ng->sdt, 'ghi_chu' => $ng->ghi_chu],
            $v['ma_don_san'] ?? null, $v['ghi_chu'] ?? null, $request->file('nhan'),
            $this->nv?->id   // ai đặt (tiền vẫn trừ ví của shop)
        );
        return response()->json(['ok' => true, 'don' => $this->donJson($don, $this->tenNguoiDat($s)), 'so_du' => $this->nv ? null : $s->soDu()]);
    }

    /** GET /api/pod/don -> đơn của shop (mới nhất trước), kèm ai đặt. Nhân viên thấy mọi đơn của shop. */
    public function don(Request $request)
    {
        $s = $this->seller($request);
        $ds = PodDon::where('seller_id', $s->id)->with('dong')->orderByDesc('id')->limit(200)->get();
        $ten = $this->tenNguoiDat($s);
        return response()->json(['ok' => true, 'co_nhan_vien' => count($ten) > 0, 'don' => $ds->map(fn ($d) => $this->donJson($d, $ten))->all()]);
    }

    /** id nhân viên -> tên (mọi nhân viên của shop, kể cả đã khoá) để ghi "Người đặt". */
    private function tenNguoiDat(PodSeller $s): array
    {
        return PodNhanVien::where('seller_id', $s->id)->pluck('ten', 'id')->all();
    }

    /** POST /api/pod/don/{ma}/huy — seller huỷ khi còn Chờ nạp tiền / Chờ in. */
    public function donHuy(Request $request, string $ma)
    {
        $s = $this->seller($request);
        $don = PodDon::where('seller_id', $s->id)->where('ma', $ma)->first();
        if (!$don) return response()->json(['ok' => false, 'error' => 'Không tìm thấy đơn.'], 404);
        if ($this->nv && (int) $don->nhan_vien_id !== $this->nv->id) {
            return response()->json(['ok' => false, 'error' => 'Nhân viên chỉ huỷ được đơn do chính mình đặt.'], 403);
        }
        if (!PodSo::huyDon($don, 'seller')) {
            return response()->json(['ok' => false, 'error' => 'Đơn đã lên máy in, không huỷ được nữa. Liên hệ xưởng.'], 422);
        }
        return response()->json(['ok' => true, 'so_du' => $this->nv ? null : $s->soDu()]);
    }

    private function donJson(PodDon $d, array $tenNv = []): array
    {
        return [
            'ma'          => $d->ma,
            'nguoi_dat'   => $d->xuong_tao ? 'Xưởng tạo hộ' : ($d->nhan_vien_id ? ($tenNv[$d->nhan_vien_id] ?? 'Nhân viên') : 'Chủ shop'),
            'xuong_tao'   => (bool) $d->xuong_tao,
            'cua_toi'     => $this->nv ? (int) $d->nhan_vien_id === $this->nv->id : true,   // nhân viên chỉ huỷ đơn mình đặt
            'trang_thai'  => $d->trang_thai,
            'ten_tt'      => $d->tenTrangThai(),
            'tong_tien'   => (int) $d->tong_tien,
            'ma_don_san'  => $d->ma_don_san,
            'ma_van_don'  => $d->ma_van_don,
            'noi_giao'    => $d->noi_giao,
            'ghi_chu'     => $d->ghi_chu,
            'co_nhan'     => (bool) $d->getRawOriginal('nhan'),
            'luc'         => optional($d->created_at)->format('d/m/Y H:i'),
            'dong'        => $d->dong->map(fn ($x) => [
                'ten_mau'      => $x->ten_mau,
                'ten_bien_the' => $x->ten_bien_the,
                'chat_lieu'    => $x->chat_lieu,
                'mau_sac'      => $x->mau_sac,
                'so_luong'     => (int) $x->so_luong,
                'chu'          => $x->chu_ca_nhan,
                'font'         => $x->font,
                'thanh_tien'   => (int) $x->thanh_tien,
            ])->all(),
        ];
    }

    /* ============ Ví ============ */

    /**
     * GET /api/pod/vi[?thang=YYYY-MM] -> số dư, sổ ví, thông tin nạp (VietQR, nội dung = NAP <mã seller>). CHỈ CHỦ SHOP.
     * Không có thang: 200 dòng gần nhất. Có thang: mọi dòng trong tháng + tổng vào/ra + số dư đầu/cuối kỳ.
     * Mỗi dòng kèm so_du_sau (số dư ngay sau giao dịch) để seller đối soát.
     */
    public function vi(Request $request)
    {
        $s = $this->chuShop($request);
        $thang = $request->query('thang');
        $thang = is_string($thang) ? $thang : '';   // ?thang[]=… là mảng -> coi như không lọc (trước đây lỗi 500)
        $soDu = $s->soDu();
        $q = PodVi::where('seller_id', $s->id);
        $tk = null;
        if (preg_match('/^(\d{4})-(0[1-9]|1[0-2])$/', $thang, $m)) {
            $tu = \Illuminate\Support\Carbon::create((int) $m[1], (int) $m[2], 1)->startOfMonth();
            $den = $tu->copy()->endOfMonth();
            $trongThang = (clone $q)->whereBetween('created_at', [$tu, $den]);
            $dong = (clone $trongThang)->orderBy('id')->limit(5000)->get();
            $dauKy = (int) (clone $q)->where('created_at', '<', $tu)->sum('so_tien');
            $chay = $dauKy;
            foreach ($dong as $d) { $chay += (int) $d->so_tien; $d->so_du_sau = $chay; }
            $dong = $dong->reverse()->values();
            // tổng tính bằng SQL trên CẢ tháng (danh sách hiển thị có thể bị cắt ở 5000 dòng)
            $vao = (int) (clone $trongThang)->where('so_tien', '>', 0)->sum('so_tien');
            $ra  = (int) (clone $trongThang)->where('so_tien', '<', 0)->sum('so_tien');
            $tk = [
                'thang'    => $thang,
                'dau_ky'   => $dauKy,
                'cuoi_ky'  => $dauKy + $vao + $ra,
                'tong_vao' => $vao,
                'tong_ra'  => -$ra,
                'so_dong'  => (int) (clone $trongThang)->count(),
            ];
        } else {
            $dong = (clone $q)->orderByDesc('id')->limit(200)->get();
            // đi lùi từ số dư TẠI dòng mới nhất đã đọc (không dùng $soDu: dòng ví mới chen giữa hai truy vấn sẽ làm lệch cả cột)
            $chay = $dong->isEmpty() ? $soDu : (int) (clone $q)->where('id', '<=', $dong->first()->id)->sum('so_tien');
            foreach ($dong as $d) { $d->so_du_sau = $chay; $chay -= (int) $d->so_tien; }
        }
        // các tháng có giao dịch (mới nhất trước) cho ô chọn tháng
        $dau = (clone $q)->min('created_at');
        $cacThang = [];
        if ($dau) {
            $c = now()->startOfMonth();
            $moc = \Illuminate\Support\Carbon::parse($dau)->startOfMonth();
            while ($c->gte($moc) && count($cacThang) < 60) { $cacThang[] = $c->format('Y-m'); $c->subMonth(); }
        }
        $bank = DB::table('admin_settings')->whereIn('key', ['bank_id', 'bank_acc', 'bank_name'])->pluck('value', 'key');
        $bin = trim((string) ($bank['bank_id'] ?? '')) ?: 'MSB';
        $stk = trim((string) ($bank['bank_acc'] ?? '')) ?: '13001011945010';
        $ten = trim((string) ($bank['bank_name'] ?? '')) ?: 'DOAN ANH TUAN';
        $noiDung = 'NAP ' . $s->ma;
        $soChoNap = (int) PodDon::where('seller_id', $s->id)->where('trang_thai', 'cho_nap')->sum('tong_tien');
        return response()->json([
            'ok'        => true,
            'so_du'     => $soDu,
            'tra_sau'   => $s->traSau(),
            // nạp thêm bao nhiêu thì mọi đơn Chờ nạp tiền chạy hết (trả sau không có đơn chờ nạp)
            'can_them'  => $soChoNap > 0 && !$s->traSau() ? max(0, $soChoNap - $soDu) : 0,
            'nap'       => [
                'ngan_hang' => $bin, 'stk' => $stk, 'chu_tk' => $ten, 'noi_dung' => $noiDung,
                'qr' => 'https://img.vietqr.io/image/' . rawurlencode($bin) . '-' . rawurlencode($stk) . '-qr_only.png?addInfo=' . rawurlencode($noiDung) . '&accountName=' . rawurlencode($ten),
            ],
            'cac_thang' => $cacThang,
            'thong_ke'  => $tk,
            'lich_su'   => $dong->map(fn ($v) => [
                'loai'      => PodVi::LOAI[$v->loai] ?? $v->loai,
                'so_tien'   => (int) $v->so_tien,
                'so_du_sau' => (int) $v->so_du_sau,
                'ghi_chu'   => $v->ghi_chu,
                'luc'       => optional($v->created_at)->format('d/m/Y H:i'),
            ])->all(),
        ]);
    }
}
