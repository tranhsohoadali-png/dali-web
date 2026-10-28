<?php
namespace App\Http\Controllers;

use App\Models\PodDon;
use App\Models\PodMau;
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
    /** Seller từ token, hoặc null. */
    private function sellerTuToken(Request $r): ?PodSeller
    {
        $token = trim((string) $r->header('X-Pod-Token', ''));
        if (strlen($token) < 20) return null;
        $p = PodPhien::where('token_hash', PodPhien::bam($token))->first();
        if (!$p) return null;
        if ($p->dung_luc && $p->dung_luc->lt(now()->subDays(PodPhien::HET_HAN_NGAY))) { $p->delete(); return null; }
        $s = PodSeller::whereKey($p->seller_id)->where('hien', true)->first();
        if (!$s) return null;
        if (!$p->dung_luc || $p->dung_luc->lt(now()->subMinutes(10))) {
            PodPhien::whereKey($p->id)->update(['dung_luc' => now(), 'ip' => $r->ip()]);
        }
        return $s;
    }

    /** Seller đã đăng nhập VÀ đã đồng ý điều khoản; không thì dừng với 401/403. */
    private function seller(Request $r): PodSeller
    {
        $s = $this->sellerTuToken($r);
        if (!$s) abort(response()->json(['ok' => false, 'error' => 'Phiên đăng nhập đã hết, vui lòng đăng nhập lại.'], 401));
        if (!$s->dong_y_luc) abort(response()->json(['ok' => false, 'error' => 'Cần đồng ý điều khoản bảo mật trước.', 'can_dong_y' => true], 403));
        return $s;
    }

    /* ============ Đăng nhập ============ */

    /** POST /api/pod/dang-nhap {sdt, matkhau} -> {ok, token} */
    public function dangNhap(Request $request)
    {
        $key = 'pod-login:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 8)) {
            return response()->json(['ok' => false, 'error' => 'Thử lại sau ít phút.'], 429);
        }
        RateLimiter::hit($key, 300);
        $v = $request->validate([
            'sdt'     => 'required|string|max:20',
            'matkhau' => 'required|string|max:100',
        ]);
        $sdt = preg_replace('/[^0-9+]/', '', $v['sdt']);
        $s = PodSeller::where('sdt', $sdt)->first();
        if (!$s || !Hash::check($v['matkhau'], $s->matkhau)) {
            return response()->json(['ok' => false, 'error' => 'Sai số điện thoại hoặc mật khẩu.'], 401);
        }
        if (!$s->hien) { // chỉ nói lý do khi đã đúng mật khẩu
            return response()->json(['ok' => false, 'error' => $s->cho_duyet
                ? 'Tài khoản đang chờ xưởng duyệt. Xưởng sẽ báo qua Zalo khi duyệt xong.'
                : 'Tài khoản đang tạm khoá, vui lòng liên hệ xưởng.'], 403);
        }
        $token = Str::random(48);
        PodPhien::create([
            'seller_id'  => $s->id,
            'token_hash' => PodPhien::bam($token),
            'thiet_bi'   => mb_substr((string) $request->userAgent(), 0, 120),
            'ip'         => $request->ip(),
            'dung_luc'   => now(),
        ]);
        $cu = PodPhien::where('seller_id', $s->id)->orderByDesc('dung_luc')->orderByDesc('id')
            ->skip(PodPhien::TOI_DA)->take(1000)->pluck('id');
        if ($cu->isNotEmpty()) PodPhien::whereIn('id', $cu)->delete();
        $s->update(['dang_nhap_luc' => now()]);
        RateLimiter::clear($key);
        return response()->json(['ok' => true, 'token' => $token]);
    }

    /** GET /api/pod/toi -> thông tin seller (cả khi chưa đồng ý điều khoản). */
    public function toi(Request $request)
    {
        $s = $this->sellerTuToken($request);
        if (!$s) return response()->json(['ok' => false], 401);
        return response()->json([
            'ok'       => true,
            'ten'      => $s->ten,
            'ma'       => $s->ma,
            'dong_y'   => (bool) $s->dong_y_luc,
            'so_du'    => $s->soDu(),
            'gia'      => PodSo::bangGia(),   // đ/gram theo chất liệu, vd {"PETG":900,"PLA":1300}
        ]);
    }

    /** POST /api/pod/dong-y — đồng ý điều khoản bảo mật (một lần). */
    public function dongY(Request $request)
    {
        $s = $this->sellerTuToken($request);
        if (!$s) return response()->json(['ok' => false], 401);
        if (!$s->dong_y_luc) $s->update(['dong_y_luc' => now()]);
        return response()->json(['ok' => true]);
    }

    /** POST /api/pod/dang-xuat — chỉ thiết bị này. */
    public function dangXuat(Request $request)
    {
        $token = trim((string) $request->header('X-Pod-Token', ''));
        if (strlen($token) >= 20) PodPhien::where('token_hash', PodPhien::bam($token))->delete();
        return response()->json(['ok' => true]);
    }

    /* ============ Kho mẫu ============ */

    /** GET /api/pod/mau?kho=chung|rieng -> mẫu seller được thấy. */
    public function mau(Request $request)
    {
        $s = $this->seller($request);
        $q = PodMau::hienCho($s->id)->with('bienThe')->orderByDesc('id');
        $kho = $request->query('kho');
        if ($kho === 'chung') $q->whereNull('chu_seller_id');
        if ($kho === 'rieng') $q->where('chu_seller_id', $s->id);
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
            'anh'         => $anh,
            'phi_ca_nhan' => (int) $m->phi_ca_nhan,
            'ten_toi_da'  => $m->tenToiDa(),          // >0 = mẫu khắc tên, bắt buộc nhập tên
            'co_xem_truoc'=> !empty($m->xem_truoc),
            'xem_truoc'   => $chiTiet ? $m->xem_truoc : null,
            'bien_the'    => $m->bienThe->map(fn ($b) => [
                'id'        => $b->id,
                'ten'       => $b->ten,
                'chat_lieu' => PodSo::chatLieu($b->chat_lieu),
                'gram'      => (float) $b->gram,
                'mau_sac'   => $b->dsMau(),
                'gia'       => PodSo::giaDong((float) $b->gram, 1, $gia[PodSo::chatLieu($b->chat_lieu)], 0, false),
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

    /* ============ Nơi giao ============ */

    public function noiGiao(Request $request)
    {
        $s = $this->seller($request);
        return response()->json(['ok' => true, 'noi_giao' => PodNoiGiao::where('seller_id', $s->id)->orderBy('id')->get(['id', 'ten', 'dia_chi', 'sdt', 'ghi_chu'])]);
    }

    public function noiGiaoLuu(Request $request)
    {
        $s = $this->seller($request);
        $v = $request->validate([
            'ten'     => 'required|string|max:120',
            'dia_chi' => 'required|string|max:300',
            'sdt'     => 'nullable|string|max:20',
            'ghi_chu' => 'nullable|string|max:300',
        ]);
        if (PodNoiGiao::where('seller_id', $s->id)->count() >= 30) {
            return response()->json(['ok' => false, 'error' => 'Tối đa 30 nơi giao.'], 422);
        }
        $n = PodNoiGiao::create($v + ['seller_id' => $s->id]);
        return response()->json(['ok' => true, 'id' => $n->id]);
    }

    public function noiGiaoXoa(Request $request, int $id)
    {
        $s = $this->seller($request);
        PodNoiGiao::where('seller_id', $s->id)->whereKey($id)->delete();
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
            $v['ma_don_san'] ?? null, $v['ghi_chu'] ?? null, $request->file('nhan')
        );
        return response()->json(['ok' => true, 'don' => $this->donJson($don), 'so_du' => $s->soDu()]);
    }

    /** GET /api/pod/don -> đơn của chính seller (mới nhất trước). */
    public function don(Request $request)
    {
        $s = $this->seller($request);
        $ds = PodDon::where('seller_id', $s->id)->with('dong')->orderByDesc('id')->limit(200)->get();
        return response()->json(['ok' => true, 'don' => $ds->map(fn ($d) => $this->donJson($d))->all()]);
    }

    /** POST /api/pod/don/{ma}/huy — seller huỷ khi còn Chờ nạp tiền / Chờ in. */
    public function donHuy(Request $request, string $ma)
    {
        $s = $this->seller($request);
        $don = PodDon::where('seller_id', $s->id)->where('ma', $ma)->first();
        if (!$don) return response()->json(['ok' => false, 'error' => 'Không tìm thấy đơn.'], 404);
        if (!PodSo::huyDon($don, 'seller')) {
            return response()->json(['ok' => false, 'error' => 'Đơn đã lên máy in, không huỷ được nữa. Liên hệ xưởng.'], 422);
        }
        return response()->json(['ok' => true, 'so_du' => $s->soDu()]);
    }

    private function donJson(PodDon $d): array
    {
        return [
            'ma'          => $d->ma,
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
                'gram'         => (float) $x->gram,
                'chu'          => $x->chu_ca_nhan,
                'thanh_tien'   => (int) $x->thanh_tien,
            ])->all(),
        ];
    }

    /* ============ Ví ============ */

    /** GET /api/pod/vi -> số dư, sổ ví, thông tin nạp (VietQR, nội dung = NAP <mã seller>). */
    public function vi(Request $request)
    {
        $s = $this->seller($request);
        $bank = DB::table('admin_settings')->whereIn('key', ['bank_id', 'bank_acc', 'bank_name'])->pluck('value', 'key');
        $bin = trim((string) ($bank['bank_id'] ?? '')) ?: 'MSB';
        $stk = trim((string) ($bank['bank_acc'] ?? '')) ?: '13001011945010';
        $ten = trim((string) ($bank['bank_name'] ?? '')) ?: 'DOAN ANH TUAN';
        $noiDung = 'NAP ' . $s->ma;
        $soChoNap = (int) PodDon::where('seller_id', $s->id)->where('trang_thai', 'cho_nap')->sum('tong_tien');
        return response()->json([
            'ok'        => true,
            'so_du'     => $s->soDu(),
            'can_them'  => max(0, $soChoNap - max(0, $s->soDu())),
            'nap'       => [
                'ngan_hang' => $bin, 'stk' => $stk, 'chu_tk' => $ten, 'noi_dung' => $noiDung,
                'qr' => 'https://img.vietqr.io/image/' . rawurlencode($bin) . '-' . rawurlencode($stk) . '-qr_only.png?addInfo=' . rawurlencode($noiDung) . '&accountName=' . rawurlencode($ten),
            ],
            'lich_su'   => PodVi::where('seller_id', $s->id)->orderByDesc('id')->limit(200)->get()->map(fn ($v) => [
                'loai'    => PodVi::LOAI[$v->loai] ?? $v->loai,
                'so_tien' => (int) $v->so_tien,
                'ghi_chu' => $v->ghi_chu,
                'luc'     => optional($v->created_at)->format('d/m/Y H:i'),
            ])->all(),
        ]);
    }
}
