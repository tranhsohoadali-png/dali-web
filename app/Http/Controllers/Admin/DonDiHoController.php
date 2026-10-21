<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DonDiHo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/** Đơn "đi đơn hộ" của đại lý (đơn TMĐT). Xem, đổi trạng thái, tải nhãn vận chuyển. */
class DonDiHoController extends Controller
{
    /** Map slug sản phẩm (đơn) → mẫu app thay tên (DS_MAU). Mẫu chưa có thì bỏ trống → soạn tay. */
    public const MAU_THAY_TEN = [
        'tkb-bau-troi'    => 'ten_bau_troi',
        'tkb-doraemon'    => 'ten_doraemon',
        'tkb-hello-kitty' => 'ten_hello_kitty',
        'tkb-dai-duong'   => 'ten_dai_duong',
        'tkb-gundam'      => 'ten_gundam',
        'tkb-spider-man'  => 'ten_spiderman',
        'bang-tkb'        => 'tkb_basic_girl',
    ];

    /** Trả mid app thay tên cho một slug, hoặc null nếu chưa nối. */
    public static function midThayTen(?string $slug): ?string
    {
        return $slug ? (self::MAU_THAY_TEN[$slug] ?? null) : null;
    }

    public function index(Request $request)
    {
        $q = DonDiHo::orderByDesc('created_at');
        if ($request->filled('tt'))     $q->where('tt', $request->tt);
        if ($request->filled('dai_ly')) $q->where('dai_ly_id', (int) $request->dai_ly);
        if ($request->tt_tt === 'chua') $q->where('da_thanh_toan', false);
        elseif ($request->tt_tt === 'da') $q->where('da_thanh_toan', true);
        if ($request->filled('search')) $q->where(fn ($w) => $w->where('ma', 'like', '%' . $request->search . '%')
            ->orWhere('dai_ly_ten', 'like', '%' . $request->search . '%')
            ->orWhere('dai_ly_sdt', 'like', '%' . $request->search . '%'));
        $orders = $q->paginate(20)->withQueryString();
        $tt     = DonDiHo::TRANG_THAI;
        $moi    = DonDiHo::where('tt', 'moi')->count();
        return view('admin.diho.index', compact('orders', 'tt', 'moi'));
    }

    /** Đối soát đại lý: gộp tổng số đơn / số lượng / tiền sỉ theo từng đại lý (lọc theo ngày). */
    public function doiSoat(Request $request)
    {
        $tu  = $request->filled('tu')  ? $request->date('tu')->startOfDay()  : null;
        $den = $request->filled('den') ? $request->date('den')->endOfDay()   : null;
        $base = DonDiHo::query()->where('tt', '!=', 'huy');
        if ($tu)  $base->where('created_at', '>=', $tu);
        if ($den) $base->where('created_at', '<=', $den);

        $rows = (clone $base)
            ->selectRaw('dai_ly_id, dai_ly_ten, dai_ly_sdt,
                COUNT(*) as so_don, SUM(so_luong) as tong_sl, SUM(tong_si) as tong_tien,
                SUM(CASE WHEN da_thanh_toan = 0 THEN tong_si ELSE 0 END) as chua_tien,
                SUM(CASE WHEN da_thanh_toan = 0 THEN 1 ELSE 0 END) as chua_don')
            ->groupBy('dai_ly_id', 'dai_ly_ten', 'dai_ly_sdt')
            ->orderByDesc('chua_tien')->orderByDesc('tong_tien')
            ->get();

        // Tiền đại lý đã trả trước (phần lẻ chưa đủ trọn 1 đơn) + sổ thu gần đây
        $ids      = $rows->pluck('dai_ly_id')->unique()->values();
        $traTruoc = \App\Models\DaiLy::whereIn('id', $ids)->pluck('tra_truoc', 'id');
        $soThu    = \App\Models\DaiLyThuTien::whereIn('dai_ly_id', $ids)->orderByDesc('id')->get()->groupBy('dai_ly_id')
            ->map(fn ($g) => $g->take(5));

        $tong = [
            'don'       => (int) $rows->sum('so_don'),
            'sl'        => (int) $rows->sum('tong_sl'),
            'tien'      => (int) $rows->sum('tong_tien'),
            'chua'      => (int) $rows->sum('chua_tien'),
            'tra_truoc' => (int) $traTruoc->sum(),
        ];
        return view('admin.diho.doisoat', compact('rows', 'tong', 'tu', 'den', 'traTruoc', 'soThu'));
    }

    /**
     * Thu tiền đại lý TỪNG PHẦN: số tiền thực nhận (+ phần trả trước còn lại) trừ vào đơn CŨ NHẤT trước;
     * đơn đủ tiền -> "Đã thu" (cộng hoa hồng người giới thiệu như bình thường); phần lẻ giữ làm trả trước.
     */
    public function thuMotPhan(Request $request)
    {
        $request->validate(['dai_ly_id' => 'required|integer', 'ghi_chu' => 'nullable|string|max:200']);
        $soTien = (int) preg_replace('/\D/', '', (string) $request->input('so_tien', ''));   // nhận "500.000", "500000"
        if ($soTien <= 0 || $soTien > 1000000000) return back()->withErrors(['so_tien' => 'Số tiền không hợp lệ.']);

        $kq = DB::transaction(function () use ($request, $soTien) {
            $dl = \App\Models\DaiLy::whereKey((int) $request->dai_ly_id)->lockForUpdate()->firstOrFail();
            $truoc = (int) $dl->tra_truoc;
            $quy = $soTien + $truoc;
            $danhDau = []; $daTru = 0;
            $chua = DonDiHo::where('dai_ly_id', $dl->id)->where('da_thanh_toan', false)->where('tt', '!=', 'huy')
                ->orderBy('created_at')->orderBy('id')->get();
            foreach ($chua as $d) {
                if ((int) $d->tong_si > $quy) break;          // FIFO chặt: đơn cũ chưa đủ tiền thì dừng, không nhảy cóc
                $quy -= (int) $d->tong_si; $daTru += (int) $d->tong_si;
                $d->update(['da_thanh_toan' => true, 'thanh_toan_luc' => now()]);
                $danhDau[] = $d->id;
            }
            $dl->update(['tra_truoc' => $quy]);
            \App\Models\DaiLyThuTien::create([
                'dai_ly_id' => $dl->id, 'so_tien' => $soTien,
                'ghi_chu' => trim((string) $request->input('ghi_chu', '')) ?: null,
                'don_ids' => $danhDau, 'tra_truoc_truoc' => $truoc, 'tra_truoc_sau' => $quy,
            ]);
            $conNo = (int) DonDiHo::where('dai_ly_id', $dl->id)->where('da_thanh_toan', false)->where('tt', '!=', 'huy')->sum('tong_si');
            return ['ten' => $dl->ten, 'ids' => $danhDau, 'da_tru' => $daTru, 'du' => $quy, 'con_no' => max(0, $conNo - $quy)];
        });
        foreach (DonDiHo::whereIn('id', $kq['ids'])->get() as $d) \App\Services\HoaHong::dongBoDiHo($d);

        $f = fn ($n) => number_format((int) $n, 0, ',', '.') . 'đ';
        return back()->with('ok', 'Đã ghi nhận thu ' . $f($soTien) . ' từ ' . $kq['ten'] . ': '
            . (count($kq['ids']) ? 'trừ đủ ' . count($kq['ids']) . ' đơn (' . $f($kq['da_tru']) . ')' : 'chưa đủ trọn đơn nào')
            . ($kq['du'] > 0 ? '; giữ ' . $f($kq['du']) . ' trả trước (trừ vào đơn kế tiếp)' : '')
            . '. Còn nợ ' . $f($kq['con_no']) . '.');
    }

    /** Huỷ LẦN THU GẦN NHẤT của đại lý (nhập nhầm): bỏ "Đã thu" các đơn của lần đó + trả lại số trả trước cũ. */
    public function huyThuTien(\App\Models\DaiLyThuTien $phieu)
    {
        $moiNhat = \App\Models\DaiLyThuTien::where('dai_ly_id', $phieu->dai_ly_id)->where('huy', false)->orderByDesc('id')->value('id');
        if ($phieu->huy || (int) $moiNhat !== (int) $phieu->id) {
            return back()->withErrors(['huy' => 'Chỉ huỷ được lần thu GẦN NHẤT còn hiệu lực của đại lý.']);
        }
        $ids = DB::transaction(function () use ($phieu) {
            $dl = \App\Models\DaiLy::whereKey($phieu->dai_ly_id)->lockForUpdate()->firstOrFail();
            $ids = array_map('intval', (array) $phieu->don_ids);
            if ($ids) DonDiHo::whereIn('id', $ids)->update(['da_thanh_toan' => false, 'thanh_toan_luc' => null]);
            $dl->update(['tra_truoc' => (int) $phieu->tra_truoc_truoc]);
            $phieu->update(['huy' => true]);
            return $ids;
        });
        foreach (DonDiHo::whereIn('id', $ids)->get() as $d) \App\Services\HoaHong::dongBoDiHo($d);
        return back()->with('ok', 'Đã huỷ lần thu ' . number_format((int) $phieu->so_tien, 0, ',', '.') . 'đ — bỏ "Đã thu" ' . count($ids) . ' đơn, trả lại số trả trước cũ.');
    }

    /** Đảo trạng thái đã/chưa thu tiền của MỘT đơn. */
    public function danhDauTt(DonDiHo $don)
    {
        $moi = !$don->da_thanh_toan;
        $don->update(['da_thanh_toan' => $moi, 'thanh_toan_luc' => $moi ? now() : null]);
        \App\Services\HoaHong::dongBoDiHo($don); // cộng/trừ hoa hồng tuyến dưới
        return back()->with('ok', ($moi ? 'Đã đánh dấu ĐÃ thu tiền đơn ' : 'Đã bỏ đánh dấu thu tiền đơn ') . $don->ma);
    }

    /** Đánh dấu ĐÃ thu tiền cho TẤT CẢ đơn chưa đối soát của một đại lý (trong khoảng ngày). */
    public function danhDauDaiLy(Request $request)
    {
        $request->validate(['dai_ly_id' => 'required|integer']);
        $q = DonDiHo::where('dai_ly_id', (int) $request->dai_ly_id)->where('da_thanh_toan', false)->where('tt', '!=', 'huy');
        if ($request->filled('tu'))  $q->where('created_at', '>=', $request->date('tu')->startOfDay());
        if ($request->filled('den')) $q->where('created_at', '<=', $request->date('den')->endOfDay());
        $ids = (clone $q)->pluck('id');
        $n = $q->update(['da_thanh_toan' => true, 'thanh_toan_luc' => now()]);
        foreach (DonDiHo::whereIn('id', $ids)->get() as $d) \App\Services\HoaHong::dongBoDiHo($d);
        return back()->with('ok', "Đã đánh dấu {$n} đơn của đại lý là ĐÃ thu tiền.");
    }

    public function show(DonDiHo $don)
    {
        $tt = DonDiHo::TRANG_THAI;
        return view('admin.diho.show', compact('don', 'tt'));
    }

    public function updateStatus(Request $request, DonDiHo $don)
    {
        $request->validate([
            'tt'    => 'required|in:' . implode(',', array_keys(DonDiHo::TRANG_THAI)),
            'ma_vc' => 'nullable|string|max:60',
            'vc'    => 'nullable|string|max:40',
        ]);
        $upd = ['tt' => $request->tt];
        if ($request->filled('ma_vc')) $upd['ma_vc'] = trim($request->ma_vc);
        if ($request->filled('vc'))    $upd['vc']    = trim($request->vc);
        if ($request->tt === 'da_gui') $upd['gui_luc'] = now();
        $don->update($upd);
        \App\Services\HoaHong::dongBoDiHo($don); // huỷ -> trừ hoa hồng; bỏ huỷ -> cộng lại
        return back()->with('ok', 'Đã cập nhật đơn ' . $don->ma . ' → ' . (DonDiHo::TRANG_THAI[$request->tt] ?? $request->tt));
    }

    /** Đổi trạng thái HÀNG LOẠT cho nhiều đơn đã tích chọn. */
    public function capNhatTrangThaiHangLoat(Request $request)
    {
        $data = $request->validate([
            'tt'    => 'required|in:' . implode(',', array_keys(DonDiHo::TRANG_THAI)),
            'ids'   => 'required|array|min:1|max:500',
            'ids.*' => 'integer',
        ]);
        $upd = ['tt' => $data['tt']];
        if ($data['tt'] === 'da_gui') $upd['gui_luc'] = now();
        $n = DonDiHo::whereIn('id', $data['ids'])->update($upd);
        foreach (DonDiHo::whereIn('id', $data['ids'])->get() as $d) \App\Services\HoaHong::dongBoDiHo($d);
        $nhan = DonDiHo::TRANG_THAI[$data['tt']] ?? $data['tt'];
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['ok' => true, 'so_don' => $n, 'nhan' => $nhan]);
        }
        return back()->with('ok', "Đã đổi {$n} đơn → {$nhan}");
    }

    /** Nối thẳng app thay tên: sinh file 3MF in tên cho MỘT dòng SP (tên lấy nguyên văn từ đơn). */
    public function taoTenIn(Request $request, DonDiHo $don, int $idx)
    {
        @set_time_limit(90); // dựng mẫu mất ~15–40s, tránh FPM giết ở 30s
        $ct = $don->chi_tiet ?: [];
        if (!isset($ct[$idx])) return response()->json(['ok' => false, 'error' => 'Không thấy dòng sản phẩm.'], 404);
        $ten = trim((string) ($ct[$idx]['ten_in'] ?? ''));
        if ($ten === '') return response()->json(['ok' => false, 'error' => 'Dòng này không có tên in.'], 422);
        $mid = self::midThayTen($ct[$idx]['slug'] ?? null);
        if (!$mid) return response()->json(['ok' => false, 'error' => 'Sản phẩm này chưa nối app thay tên — soạn tay.'], 422);

        $res = \App\Services\ThayTenClient::taoFile($mid, $ten);
        if (empty($res['ok'])) return response()->json(['ok' => false, 'error' => $res['error'] ?? 'Không tạo được file.'], 502);

        $path = 'di-ho/' . $don->ma . '-inten-' . $idx . '.3mf';
        Storage::disk('local')->put($path, $res['noidung']);
        $ct[$idx]['ten_3mf']     = $path;
        $ct[$idx]['ten_3mf_ten'] = $res['ten_file'];
        $ct[$idx]['ten_3mf_luc'] = now()->toDateTimeString();
        $ct[$idx]['ten_3mf_cb']  = $res['canh_bao'];
        $don->chi_tiet = $ct;
        $don->save();

        return response()->json([
            'ok'       => true,
            'ten_file' => $res['ten_file'],
            'canh_bao' => $res['canh_bao'],
            'tai_url'  => route('admin.diho.taiten', ['don' => $don->id, 'idx' => $idx]),
        ]);
    }

    /** Tải file 3MF in tên đã sinh cho một dòng. */
    public function taiTenFile(DonDiHo $don, int $idx)
    {
        $ct = $don->chi_tiet ?: [];
        $p = $ct[$idx]['ten_3mf'] ?? null;
        if (!$p || !Storage::disk('local')->exists($p)) abort(404, 'Chưa có file in tên.');
        $ten = $ct[$idx]['ten_3mf_ten'] ?? ('in-ten-' . $idx . '.3mf');
        return Storage::disk('local')->download($p, $ten);
    }

    /** Mở nhãn/hoá đơn vận chuyển INLINE (xem/in ngay trong trình duyệt) — chỉ admin. */
    public function taiNhan(DonDiHo $don)
    {
        if (!$don->nhan_vc_path || !Storage::disk('local')->exists($don->nhan_vc_path)) {
            abort(404, 'Không tìm thấy file nhãn.');
        }
        $ext = strtolower(pathinfo($don->nhan_vc_path, PATHINFO_EXTENSION) ?: 'dat');
        $headers = $don->nhan_vc_mime ? ['Content-Type' => $don->nhan_vc_mime] : [];
        // response() => Content-Disposition: inline (mở thẳng, không ép tải)
        return Storage::disk('local')->response($don->nhan_vc_path, 'nhan-' . $don->ma . '.' . $ext, $headers);
    }

    /** Tải ảnh ghi chú của một dòng (VD danh sách môn của khách) — đĩa private, chỉ admin. */
    public function taiAnhMon(DonDiHo $don, int $idx)
    {
        $line = ($don->chi_tiet ?: [])[$idx] ?? null;
        $rel  = $line['anh_ghi_chu'] ?? null;
        if (!$rel || !Storage::disk('local')->exists($rel)) abort(404, 'Không tìm thấy ảnh.');
        $ext = strtolower(pathinfo($rel, PATHINFO_EXTENSION) ?: 'jpg');
        $mime = $line['anh_ghi_chu_mime'] ?? null;
        return Storage::disk('local')->response($rel, 'monanh-' . $don->ma . '-' . $idx . '.' . $ext, $mime ? ['Content-Type' => $mime] : []);
    }

    /** Tính lại giá đơn theo GIÁ SẢN PHẨM HIỆN TẠI (khi chủ xưởng sửa giá sỉ). */
    public function tinhLaiGia(DonDiHo $don)
    {
        $dl = \App\Models\DaiLy::find($don->dai_ly_id);
        $sllLuon = $dl ? (bool) $dl->sll_luon : false;
        $lines = $don->chi_tiet ?: [];
        $soLuong = 0; $thieu = [];
        foreach ($lines as $i => $l) {
            $qty = (int) ($l['qty'] ?? 1);
            $soLuong += $qty;
            $p = \App\Models\Sp3d::where('slug', $l['slug'] ?? '')->first();
            if (!$p) { $thieu[] = ($l['ten'] ?? $l['slug'] ?? '?'); continue; } // SP đã xoá -> giữ giá cũ
            $unit = (int) $p->gia_si;
            $sll = (int) $p->gia_si_sll; $tu = (int) $p->sll_tu;
            if ($unit > 0 && $sll > 0 && ($sllLuon || ($tu > 0 && $qty >= $tu))) $unit = $sll;
            $phu = ((string) ($l['ten_in'] ?? '') !== '' && (int) $p->phu_phi_ten > 0) ? (int) $p->phu_phi_ten : 0;
            $lines[$i]['don_gia_si']  = $unit;
            $lines[$i]['phu_phi_ten'] = $phu;
            $lines[$i]['thanh_tien']  = $unit * $qty + $phu;
        }
        $base = collect($lines)->sum(fn ($l) => (int) ($l['thanh_tien'] ?? 0));
        $don->update(['chi_tiet' => $lines, 'so_luong' => $soLuong, 'tong_si' => $base + (int) $don->thu_them]);
        \App\Services\HoaHong::tinhLaiDiHo($don); // tiền đổi -> hoa hồng đã cộng tính lại theo giá mới
        $msg = 'Đã tính lại giá đơn ' . $don->ma . ' theo giá hiện tại. Tổng: ' . number_format((int) $don->tong_si, 0, ',', '.') . 'đ';
        if ($thieu) $msg .= ' (bỏ qua mã đã xoá: ' . implode(', ', $thieu) . ')';
        return back()->with('ok', $msg);
    }

    /** Chi phí thu thêm (admin nhập) + ghi chú; cộng lại vào tong_si để đối soát đúng. */
    public function capNhatThuThem(Request $request, DonDiHo $don)
    {
        $request->validate([
            'thu_them'    => 'nullable|integer|min:0|max:100000000',
            'thu_them_gc' => 'nullable|string|max:200',
        ]);
        $thuThem = (int) $request->input('thu_them', 0);
        $base = collect($don->chi_tiet ?: [])->sum(fn ($l) => (int) ($l['thanh_tien'] ?? 0));
        $don->update([
            'thu_them'    => $thuThem,
            'thu_them_gc' => trim((string) $request->input('thu_them_gc', '')) ?: null,
            'tong_si'     => $base + $thuThem,
        ]);
        \App\Services\HoaHong::tinhLaiDiHo($don);
        return back()->with('ok', 'Đã cập nhật chi phí thu thêm cho đơn ' . $don->ma);
    }

    /**
     * Chuyển đơn sang soạn TKB: lưu danh sách môn (vừa đọc từ ảnh) vào đơn để làm checklist.
     * Danh sách gắn theo DÒNG đơn ('dong'): đơn 2 bảng TKB chuyển dòng 2 không xoá danh sách dòng 1
     * (lỗi cũ: DH261015-B35R0T mất cả 40 thẻ lớp 3A3). Mục cũ chưa có 'dong': bỏ, trừ mục đã tick (giữ công thợ).
     */
    public function luuSoan(Request $request, DonDiHo $don)
    {
        $mon = $request->input('mon', []);
        if (is_string($mon)) $mon = json_decode($mon, true);
        if (!is_array($mon)) $mon = [];
        $dong = $request->input('dong');
        $dong = ($dong === null || $dong === '') ? null : (int) $dong;
        $moi = [];
        foreach ($mon as $m) {
            if (!is_array($m)) continue;
            $ten = mb_substr(trim((string) ($m['mon'] ?? '')), 0, 40);
            if ($ten === '') continue;
            $sl = max(1, min(999, (int) ($m['so_luong'] ?? $m['sl'] ?? 1)));
            $moi[] = ['mon' => $ten, 'sl' => $sl, 'xong' => false] + ($dong === null ? [] : ['dong' => $dong]);
            if (count($moi) >= 100) break;
        }
        if (!$moi) return back()->with('ok', 'Chưa có môn nào để soạn (bấm "Đọc số môn" trước).');
        $giu = [];
        if ($dong !== null) {
            foreach (($don->mon_soan ?: []) as $m) {
                $d = $m['dong'] ?? null;
                if (($d !== null && (int) $d !== $dong) || ($d === null && !empty($m['xong']))) $giu[] = $m;
            }
        }
        $don->update(['mon_soan' => array_merge($giu, $moi)]);
        return redirect()->route('admin.diho.soan', $don)->with('ok', 'Đã chuyển ' . ($dong === null ? 'đơn ' : 'dòng ' . ($dong + 1) . ' đơn ') . $don->ma . ' sang bảng soạn TKB (' . count($moi) . ' môn).');
    }

    /** Bảng soạn TKB của đơn: thông tin in + checklist môn để thợ làm, in được. */
    public function soan(DonDiHo $don)
    {
        return view('admin.diho.soan', ['don' => $don]);
    }

    /** Tick/bỏ tick một môn đã soạn xong (AJAX). */
    public function tickSoan(Request $request, DonDiHo $don)
    {
        $idx = (int) $request->input('idx', -1);
        $ds = $don->mon_soan ?: [];
        if (!isset($ds[$idx])) return response()->json(['ok' => false], 404);
        $ds[$idx]['xong'] = !($ds[$idx]['xong'] ?? false);
        $don->update(['mon_soan' => $ds]);
        return response()->json(['ok' => true, 'xong' => $ds[$idx]['xong']]);
    }

    /** AI đọc mã vận đơn từ nhãn vận chuyển đã tải lên (điền ma_vc/vc). */
    public function docNhanVc(Request $request, DonDiHo $don)
    {
        $ajax = $request->expectsJson() || $request->ajax();
        $tra = function (bool $ok, string $msg, array $them = []) use ($ajax, $don) {
            if ($ajax) return response()->json(array_merge(['ok' => $ok, 'msg' => $msg, 'ma_vc' => $don->ma_vc, 'vc' => $don->vc], $them), $ok ? 200 : 200);
            return back()->with('ok', $msg);
        };
        if (!$don->nhan_vc_path || !Storage::disk('local')->exists($don->nhan_vc_path)) {
            return $tra(false, 'Đơn chưa có nhãn vận chuyển.');
        }
        // 1) Đọc KHÔNG cần AI: rút lớp chữ PDF + dò mẫu (miễn phí, nhanh)
        $full = Storage::disk('local')->path($don->nhan_vc_path);
        $r = \App\Services\NhanVcReader::doc($full, (string) $don->nhan_vc_mime);
        if (!empty($r['ok'])) {
            $don->update(['ma_vc' => $r['ma_vc'], 'vc' => ($r['vc'] ?? '') ?: $don->vc]);
            $don->refresh();
            return $tra(true, '📄 Đọc từ chữ trên nhãn (không cần AI): ' . $r['ma_vc'] . ($r['vc'] ? ' (' . $r['vc'] . ')' : ''));
        }
        // 2) Nhãn là ẢNH / PDF scan không có chữ → nhờ AI (nếu đã bật)
        if (!\App\Services\DocMonAi::batAi()) {
            return $tra(false, 'Nhãn không có lớp chữ để đọc tự động (có thể là ảnh). Bật AI hoặc nhập tay.');
        }
        $res = \App\Services\DocMonAi::docNhanVc(Storage::disk('local')->get($don->nhan_vc_path), (string) $don->nhan_vc_mime);
        if (empty($res['ok'])) return $tra(false, 'Chưa đọc được nhãn (' . ($res['error'] ?? '') . ').');
        if (($res['ma_vc'] ?? '') !== '') {
            $don->update(['ma_vc' => $res['ma_vc'], 'vc' => ($res['vc'] ?? '') ?: $don->vc]);
            $don->refresh();
            return $tra(true, '🤖 AI đọc mã vận đơn: ' . $res['ma_vc'] . ($res['vc'] ? ' (' . $res['vc'] . ')' : ''));
        }
        return $tra(false, 'Không tìm thấy mã vận đơn trên nhãn — nhập tay giúp.');
    }

    /** AI đọc số môn từ ảnh ghi chú của một dòng (dùng cho xưởng ngay trong admin). */
    public function docMonAi(DonDiHo $don, int $idx)
    {
        $line = ($don->chi_tiet ?: [])[$idx] ?? null;
        $rel  = $line['anh_ghi_chu'] ?? null;
        if (!$rel || !Storage::disk('local')->exists($rel)) {
            return response()->json(['ok' => false, 'error' => 'Dòng này không có ảnh ghi chú.'], 404);
        }
        @set_time_limit(120);
        // AI đọc chính, máy (OCR) dự phòng — chủ xưởng chốt 2026-10-21 (máy hay sót ô: đơn DH261015-B35R0T đọc 15/40)
        $full = Storage::disk('local')->path($rel);
        $mime = (string) ($line['anh_ghi_chu_mime'] ?? '') ?: (string) @mime_content_type($full);
        $res = \App\Services\DocMonTkb::doc($full, $mime);
        return response()->json($res, $res['ok'] ? 200 : 422);
    }

    public function destroy(DonDiHo $don)
    {
        $ma = $don->ma;
        if ($don->nhan_vc_path) Storage::disk('local')->delete($don->nhan_vc_path);
        // Xoá cả ảnh ghi chú từng dòng (nếu có)
        foreach (($don->chi_tiet ?: []) as $l) {
            if (!empty($l['anh_ghi_chu'])) Storage::disk('local')->delete($l['anh_ghi_chu']);
        }
        \App\Services\HoaHong::dongBoDiHo($don, true); // gỡ hoa hồng đã cộng TRƯỚC khi xoá
        $don->delete();
        return redirect()->route('admin.diho.index')->with('ok', 'Đã xoá đơn ' . $ma);
    }
}
