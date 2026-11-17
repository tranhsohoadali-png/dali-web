<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PodAnhXa;
use App\Models\PodBienThe;
use App\Models\PodDon;
use App\Models\PodMau;
use App\Models\PodNoiGiao;
use App\Models\PodSeller;
use App\Services\PodNhapDon;
use App\Services\PodSo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/** Cổng POD: hàng đợi in + đơn seller. Mặc định chỉ hiện đơn cần làm (Chờ in, Đang in). */
class PodDonController extends Controller
{
    /**
     * Xưởng tạo đơn hộ seller (seller gửi đơn qua Zalo...). Chỉ chọn được mẫu seller đó thấy (PodMau::hienCho — giữ kín
     * mẫu riêng người khác); chữ/màu/font kiểm như seller tự đặt (PodSo::tinhDong); tiền trừ ví seller, thiếu thì Chờ nạp tiền.
     */
    public function tao(Request $request)
    {
        $sellers = PodSeller::where('cho_duyet', false)->orderBy('ten')->get();
        $s = $request->filled('seller') ? $sellers->firstWhere('id', (int) $request->query('seller')) : null;
        [$mau, $bt] = $s ? $this->mauCuaSeller($s) : [collect(), []];
        $noiGiao = $s ? PodNoiGiao::where('seller_id', $s->id)->orderBy('id')->get() : collect();
        return view('admin.pod.don-tao', compact('sellers', 's', 'mau', 'noiGiao', 'bt'));
    }

    /** Mẫu seller này thấy (hienCho) + thông tin từng phân loại cho JS (giá, màu, font, gợi ý chữ). Bỏ phân loại cũ. */
    private function mauCuaSeller(PodSeller $s): array
    {
        $mau = PodMau::hienCho($s->id)->with('bienThe')->orderBy('ten')->get();
        $bangGia = PodSo::bangGia();
        $bt = [];
        foreach ($mau as $m) {
            foreach ($m->bienThe as $b) {
                if ($b->laCu()) continue;
                $toiDa = $m->tenToiDa($b->kieu);
                $bt[$b->id] = [
                    'gia'   => PodSo::giaBienThe($m, $b, $bangGia),
                    'phi'   => (int) $m->phi_ca_nhan,
                    'mau'   => $b->dsMau(),
                    'font'  => $m->dsFont(),
                    'chu'   => $toiDa > 0,
                    'goi_y' => ($toiDa <= 0 ? 'Chữ cá nhân hoá (không bắt buộc)'
                        : ($m->laDai() ? 'Bắt buộc: TÊN / NĂM' : ($m->laGiaDinh() ? 'Bắt buộc: Tên 1 / Tên 2 / …' : 'Bắt buộc: tên in, tối đa ' . $toiDa . ' ký tự')))
                        . ($m->phi_ca_nhan ? ' · +' . number_format($m->phi_ca_nhan, 0, ',', '.') . 'đ' : ''),
                ];
            }
        }
        return [$mau, $bt];
    }

    /** Nơi giao từ form (chọn nơi giao đã lưu của seller hoặc "moi" + nhập tay). Trả mảng nơi giao, hoặc chuỗi lỗi. */
    private function noiGiaoTuForm(PodSeller $s, array $v): array|string
    {
        $chon = (string) ($v['noi_giao_id'] ?? '');
        if ($chon !== '' && $chon !== 'moi') {
            $ng = PodNoiGiao::where('seller_id', $s->id)->whereKey((int) $chon)->first();
            if (!$ng) return 'Nơi giao không thuộc seller này.';
            return ['ten' => $ng->ten, 'dia_chi' => $ng->dia_chi, 'sdt' => $ng->sdt, 'ghi_chu' => $ng->ghi_chu];
        }
        $ten = trim((string) ($v['ng_ten'] ?? ''));
        $diaChi = trim((string) ($v['ng_dia_chi'] ?? ''));
        if ($ten === '' || $diaChi === '') return 'Chọn nơi giao của seller, hoặc nhập tên + địa chỉ nơi giao mới.';
        return ['ten' => $ten, 'dia_chi' => $diaChi, 'sdt' => trim((string) ($v['ng_sdt'] ?? '')) ?: null, 'ghi_chu' => trim((string) ($v['ng_ghi_chu'] ?? '')) ?: null];
    }

    private const LUAT_NOI_GIAO = [
        'noi_giao_id' => 'nullable|string|max:20',
        'ng_ten'      => 'nullable|string|max:120',
        'ng_dia_chi'  => 'nullable|string|max:500',
        'ng_sdt'      => 'nullable|string|max:30',
        'ng_ghi_chu'  => 'nullable|string|max:300',
    ];

    /** POST don/nhap — đọc file đơn seller gửi (.xlsx/.csv), giữ tạm trong phiên, sang trang khớp phân loại. */
    public function nhapDoc(Request $request)
    {
        $request->validate(['seller_id' => 'required|integer', 'file' => 'required|file|max:5120']);
        $s = PodSeller::where('cho_duyet', false)->find((int) $request->input('seller_id'));
        if (!$s) return back()->withErrors(['file' => 'Chọn seller trước khi nhập file.']);
        try {
            $kq = PodNhapDon::doc($request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return back()->withErrors(['file' => $e->getMessage()]);
        }
        $k = Str::random(16);
        session(["pod_nhap.$k" => ['seller_id' => $s->id, 'ten_file' => mb_substr($request->file('file')->getClientOriginalName(), 0, 120)] + $kq]);
        return redirect()->route('admin.pod.don.nhap', ['k' => $k]);
    }

    /** GET don/nhap?k= — khớp "tên sản phẩm + kích thước" trong file với phân loại (nhớ theo seller), xem trước các đơn. */
    public function nhap(Request $request)
    {
        $k = (string) $request->query('k');
        $nh = session("pod_nhap.$k");
        if (!$nh) return redirect()->route('admin.pod.don.tao')->withErrors(['file' => 'Phiên nhập file đã hết, tải file lại.']);
        $s = PodSeller::findOrFail($nh['seller_id']);
        [$mau, $bt] = $this->mauCuaSeller($s);
        $dong = collect($nh['dong']);
        $nhom = $dong->groupBy(fn ($d) => PodNhapDon::khoa($d['ten'], $d['co']));
        $nho = PodAnhXa::where('seller_id', $s->id)->whereIn('khoa', $nhom->keys()->all())->get()->keyBy('khoa');
        $daCo = PodDon::where('seller_id', $s->id)->whereIn('ma_don_san', $dong->pluck('ma')->filter()->unique()->values()->all())
            ->where('trang_thai', '!=', 'da_huy')->pluck('ma', 'ma_don_san');
        $noiGiao = PodNoiGiao::where('seller_id', $s->id)->orderBy('id')->get();
        return view('admin.pod.don-nhap', compact('k', 'nh', 's', 'mau', 'bt', 'dong', 'nhom', 'nho', 'daCo', 'noiGiao'));
    }

    /**
     * POST don/nhap/tao — tạo đơn hộ hàng loạt: mỗi mã đơn trong file một đơn (dòng cùng mã gộp chung), hoặc gộp tất cả.
     * Mã đơn đã có đơn (chưa huỷ) thì bỏ qua. Lỗi của một đơn (chữ thiếu, màu sai...) không chặn các đơn khác.
     */
    public function nhapTao(Request $request)
    {
        $v = $request->validate([
            'k'        => 'required|string|max:40',
            'khop'     => 'required|array',
            'khop.*'   => 'nullable|integer',
            'mau'      => 'nullable|array',
            'mau.*'    => 'nullable|string|max:60',
            'gop'      => 'nullable|in:0,1',
        ] + self::LUAT_NOI_GIAO);
        $nh = session("pod_nhap.{$v['k']}");
        if (!$nh) return redirect()->route('admin.pod.don.tao')->withErrors(['file' => 'Phiên nhập file đã hết, tải file lại.']);
        $s = PodSeller::findOrFail($nh['seller_id']);
        if (!$s->hien) return back()->withErrors(['seller_id' => 'Seller "' . $s->ten . '" đang bị khoá.'])->withInput();
        $noiGiao = $this->noiGiaoTuForm($s, $v);
        if (is_string($noiGiao)) return back()->withErrors(['noi_giao_id' => $noiGiao])->withInput();

        // khoá gửi lên là chuỗi base64 của khoá thật (khoá có dấu cách, "|", chữ có dấu)
        $khop = $mauMd = [];
        foreach ((array) $v['khop'] as $kb => $id) $khop[(string) base64_decode((string) $kb)] = (int) $id;
        foreach ((array) ($v['mau'] ?? []) as $kb => $m) $mauMd[(string) base64_decode((string) $kb)] = trim((string) $m);
        $dong = collect($nh['dong'])->map(fn ($d) => $d + ['khoa' => PodNhapDon::khoa($d['ten'], $d['co'])]);
        $thieu = $dong->pluck('khoa')->unique()->filter(fn ($kh) => empty($khop[$kh]));
        if ($thieu->isNotEmpty()) {
            return back()->withErrors(['khop' => 'Chọn phân loại cho: ' . $dong->whereIn('khoa', $thieu->all())->map(fn ($d) => $d['ten'] . ($d['co'] !== '' ? ' (' . $d['co'] . ')' : ''))->unique()->implode('; ')])->withInput();
        }
        foreach ($khop as $kh => $id) {
            if ($dong->contains('khoa', $kh)) {
                PodAnhXa::updateOrCreate(['seller_id' => $s->id, 'khoa' => $kh], ['bien_the_id' => $id, 'mau_sac' => ($mauMd[$kh] ?? '') ?: null]);
            }
        }

        $gop = ($v['gop'] ?? '0') === '1';
        $nhom = $gop ? collect(['' => $dong]) : $dong->groupBy(fn ($d) => $d['ma'] !== '' ? 'ma:' . $d['ma'] : 'dong:' . $d['so']);
        $tao = $bo = $loi = [];
        foreach ($nhom as $ds) {
            $ma = $gop ? null : (($ds->first()['ma'] ?? '') ?: null);
            $nhan = $ma ?? 'dòng ' . $ds->pluck('so')->implode(', ');
            if ($ma && ($cu = PodDon::where('seller_id', $s->id)->where('ma_don_san', $ma)->where('trang_thai', '!=', 'da_huy')->value('ma'))) {
                $bo[] = "$ma (đã có đơn $cu)";
                continue;
            }
            $lines = $ds->map(fn ($d) => ['bien_the_id' => $khop[$d['khoa']], 'so_luong' => max(1, (int) $d['sl']), 'chu' => $d['chu'],
                'mau_sac' => $d['mau'] !== '' ? $d['mau'] : ($mauMd[$d['khoa']] ?? ''), 'font' => ''])->values()->all();
            $ghi = 'Nhập từ file "' . $nh['ten_file'] . '"' . ($gop ? ' · mã: ' . $ds->pluck('ma')->filter()->unique()->implode(', ') : '')
                . (($n = $ds->pluck('ghi_chu')->filter()->unique()->implode(' · ')) !== '' ? ' · ' . $n : '');
            try {
                $tao[] = PodSo::datDon($s, $lines, $noiGiao, $ma, mb_substr($ghi, 0, 1000), null, null, true);
            } catch (ValidationException $e) {
                $loi[] = $nhan . ': ' . collect($e->errors())->flatten()->first();
            }
        }
        session()->forget("pod_nhap.{$v['k']}");
        $tien = fn ($x) => number_format($x, 0, ',', '.') . 'đ';
        $cl = collect($tao);
        $bao = 'Nhập file cho ' . $s->ten . ': tạo ' . $cl->count() . ' đơn · ' . $tien($cl->sum('tong_tien'))
            . ($cl->count() ? ' (' . $cl->where('trang_thai', 'cho_in')->count() . ' Chờ in, ' . $cl->where('trang_thai', 'cho_nap')->count() . ' Chờ nạp tiền)' : '') . '.'
            . ($bo ? ' Bỏ qua ' . count($bo) . ' mã đã có đơn: ' . implode('; ', $bo) . '.' : '');
        $r = redirect()->route('admin.pod.don.index', ['tt' => 'tat_ca'])->with('ok', $bao);
        return $loi ? $r->withErrors(['nhap' => 'Không tạo được ' . count($loi) . ' đơn — ' . implode(' | ', $loi)]) : $r;
    }

    /**
     * POST don/{don}/thong-tin — sửa nơi giao / mã đơn sàn / ghi chú khi đơn chưa giao, chưa huỷ.
     * Tuỳ chọn: áp nơi giao mới cho mọi đơn chưa giao khác của seller đang ghi CÙNG nơi giao cũ (vd 12 đơn "chờ địa chỉ"),
     * và lưu vào sổ nơi giao của seller.
     */
    public function thongTin(Request $request, PodDon $don)
    {
        if (in_array($don->trang_thai, ['da_giao', 'da_huy'], true)) return back()->withErrors(['thong_tin' => 'Đơn đã giao hoặc đã huỷ, không sửa được.']);
        $v = $request->validate(self::LUAT_NOI_GIAO + [
            'ma_don_san' => 'nullable|string|max:60',
            'ghi_chu'    => 'nullable|string|max:1000',
            'ap_cung'    => 'nullable|boolean',
            'luu_so'     => 'nullable|boolean',
        ]);
        $s = PodSeller::findOrFail($don->seller_id);
        $moi = $this->noiGiaoTuForm($s, $v);
        if (is_string($moi)) return back()->withErrors(['thong_tin' => $moi])->withInput();
        $cu = (array) $don->noi_giao;
        $giong = fn ($d) => trim((string) (($d->noi_giao['ten'] ?? ''))) === trim((string) ($cu['ten'] ?? '')) && trim((string) ($d->noi_giao['dia_chi'] ?? '')) === trim((string) ($cu['dia_chi'] ?? ''));
        $n = 0;
        DB::transaction(function () use ($don, $v, $moi, $s, $giong, $request, &$n) {
            $don->update(['noi_giao' => $moi, 'ma_don_san' => trim((string) ($v['ma_don_san'] ?? '')) ?: null, 'ghi_chu' => trim((string) ($v['ghi_chu'] ?? '')) ?: null]);
            if ($request->boolean('ap_cung')) {
                foreach (PodDon::where('seller_id', $s->id)->whereKeyNot($don->id)->whereIn('trang_thai', ['cho_nap', 'cho_in', 'dang_in'])->get()->filter($giong) as $d) {
                    $d->update(['noi_giao' => $moi]);
                    $n++;
                }
            }
            if ($request->boolean('luu_so') && !PodNoiGiao::where('seller_id', $s->id)->where('ten', $moi['ten'])->where('dia_chi', $moi['dia_chi'])->exists()) {
                PodNoiGiao::create(['seller_id' => $s->id] + $moi);
            }
        });
        return back()->with('ok', 'Đã cập nhật thông tin giao đơn ' . $don->ma . '.' . ($n ? " Áp cùng nơi giao mới cho $n đơn khác của " . $s->ten . '.' : '')
            . ($request->boolean('luu_so') ? ' Đã lưu vào sổ nơi giao của seller.' : ''));
    }

    public function taoLuu(Request $request)
    {
        $v = $request->validate([
            'seller_id'          => 'required|integer',
            'dong'               => 'required|array|max:50',
            'dong.*.bien_the_id' => 'nullable|integer',
            'dong.*.so_luong'    => 'nullable|integer|min:1|max:' . PodSo::SL_TOI_DA,
            'dong.*.mau_sac'     => 'nullable|string|max:60',
            'dong.*.chu'         => 'nullable|string|max:300',
            'dong.*.font'        => 'nullable|string|max:80',
            'noi_giao_id'        => 'nullable|string|max:20',
            'ng_ten'             => 'nullable|string|max:120',
            'ng_dia_chi'         => 'nullable|string|max:500',
            'ng_sdt'             => 'nullable|string|max:30',
            'ng_ghi_chu'         => 'nullable|string|max:300',
            'ma_don_san'         => 'nullable|string|max:60',
            'ghi_chu'            => 'nullable|string|max:1000',
            'nhan'               => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf|max:10240',
        ]);
        $s = PodSeller::where('cho_duyet', false)->find((int) $v['seller_id']);
        if (!$s) return back()->withErrors(['seller_id' => 'Chọn seller.'])->withInput();
        if (!$s->hien) return back()->withErrors(['seller_id' => 'Seller "' . $s->ten . '" đang bị khoá, mở khoá trước khi tạo đơn.'])->withInput();
        // bỏ dòng trống, đánh lại số dòng theo thứ tự điền
        $dong = array_values(array_map(fn ($d) => ['so_luong' => (int) ($d['so_luong'] ?? 0) ?: 1] + $d,
            array_filter($v['dong'], fn ($d) => !empty($d['bien_the_id']))));
        if (!$dong) return back()->withErrors(['dong' => 'Chọn phân loại cho ít nhất một dòng.'])->withInput();

        $noiGiao = $this->noiGiaoTuForm($s, $v);
        if (is_string($noiGiao)) return back()->withErrors(['noi_giao_id' => $noiGiao])->withInput();

        $don = PodSo::datDon($s, $dong, $noiGiao, trim((string) ($v['ma_don_san'] ?? '')) ?: null,
            trim((string) ($v['ghi_chu'] ?? '')) ?: null, $request->file('nhan'), null, true);
        $tien = fn ($x) => number_format($x, 0, ',', '.') . 'đ';
        return redirect()->route('admin.pod.don.show', $don)->with('ok', 'Đã tạo đơn ' . $don->ma . ' hộ ' . $s->ten . ' · ' . $tien($don->tong_tien) . '. '
            . ($don->trang_thai === 'cho_in'
                ? 'Đã trừ ví, đơn vào hàng đợi in.' . (($du = $s->soDu()) < 0 ? ' Seller trả sau, đang nợ ' . $tien(-$du) . '.' : '')
                : 'Ví seller chưa đủ (số dư ' . $tien($s->soDu()) . '): đơn Chờ nạp tiền, tự vào sản xuất khi seller nạp đủ.'));
    }

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
        $mau = \App\Models\PodMau::with('bienThe')->whereIn('id', $don->dong->pluck('mau_id'))->get()->keyBy('id');
        $taoFile = $don->dong->mapWithKeys(fn ($x) => [$x->id => $mau->get($x->mau_id)?->thamSoFileIn($x->ten_bien_the, $x->chu_ca_nhan, $x->mau_sac, $x->chat_lieu)])->filter()->all();
        $tepIn = $don->dong->mapWithKeys(fn ($x) => [$x->id => $mau->get($x->mau_id)?->tepInCho($fileIn[$x->bien_the_id] ?? null) ?? []])->filter()->all();
        $noiGiaoSeller = PodNoiGiao::where('seller_id', $don->seller_id)->orderBy('id')->get();
        $ng = (array) $don->noi_giao;
        $soCung = PodDon::where('seller_id', $don->seller_id)->whereKeyNot($don->id)->whereIn('trang_thai', ['cho_nap', 'cho_in', 'dang_in'])->get()
            ->filter(fn ($d) => trim((string) ($d->noi_giao['ten'] ?? '')) === trim((string) ($ng['ten'] ?? '')) && trim((string) ($d->noi_giao['dia_chi'] ?? '')) === trim((string) ($ng['dia_chi'] ?? '')))->count();
        return view('admin.pod.don-show', compact('don', 'fileIn', 'coNhan', 'taoFile', 'tepIn', 'noiGiaoSeller', 'soCung'));
    }

    /**
     * Tải file in của một dòng đơn, tự chọn đúng file theo phân loại (PodMau::tepInCho):
     * 1 file -> tải thẳng; nhiều file (vd thân + đế) -> gói .zip một lần bấm.
     */
    public function taiFileIn(PodDon $don, int $dong)
    {
        $x = $don->dong()->whereKey($dong)->firstOrFail();
        $m = \App\Models\PodMau::with('bienThe')->find($x->mau_id);
        $tep = $m ? $m->tepInCho(PodBienThe::whereKey($x->bien_the_id)->value('file_in')) : [];
        if (!$tep) abort(404);
        if ($m->laRieng()) \App\Models\PodNhatKy::ghi($m->id, 'tai_file_in', 'Đơn ' . $don->ma . ': ' . implode(', ', array_column($tep, 'ten')));
        $d = Storage::disk('local');
        if (count($tep) === 1) return $d->download($tep[0]['rel'], $tep[0]['ten']);
        $ten = trim(preg_replace('/[^\p{L}\p{N} ._()+\-]+/u', '_', $don->ma . ' - ' . $x->ten_mau . ' - ' . $x->ten_bien_the), ' .') . '.zip';
        $tam = tempnam(sys_get_temp_dir(), 'podin');
        $zip = new \ZipArchive();
        $zip->open($tam, \ZipArchive::OVERWRITE);
        foreach ($tep as $t) {
            $zip->addFile($d->path($t['rel']), $t['ten']);
            $zip->setCompressionName($t['ten'], \ZipArchive::CM_STORE); // 3MF đã nén sẵn
        }
        $zip->close();
        return response()->download($tam, $ten, ['Content-Type' => 'application/zip'])->deleteFileAfterSend();
    }

    /** Chờ in -> Đang in -> Đã giao (kèm mã vận đơn). Không lùi trạng thái; huỷ dùng nút riêng. */
    public function trangThai(Request $request, PodDon $don)
    {
        $v = $request->validate([
            'trang_thai' => 'required|in:dang_in,da_giao',
            'ma_van_don' => 'nullable|string|max:60',
        ]);
        $loi = PodSo::chuyenTrangThai($don, $v['trang_thai'], $v['ma_van_don'] ?? null, 'Admin');
        if ($loi) return back()->withErrors(['trang_thai' => $loi]);
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
