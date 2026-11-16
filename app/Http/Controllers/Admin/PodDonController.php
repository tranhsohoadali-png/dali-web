<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PodBienThe;
use App\Models\PodDon;
use App\Models\PodMau;
use App\Models\PodNoiGiao;
use App\Models\PodSeller;
use App\Services\PodSo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        $mau = collect();
        $noiGiao = collect();
        $bt = [];
        if ($s) {
            $mau = PodMau::hienCho($s->id)->with('bienThe')->orderBy('ten')->get();
            $noiGiao = PodNoiGiao::where('seller_id', $s->id)->orderBy('id')->get();
            $bangGia = PodSo::bangGia();
            foreach ($mau as $m) {
                foreach ($m->bienThe as $b) {
                    if ($b->laCu()) continue;
                    $toiDa = $m->tenToiDa($b->kieu);
                    $bt[$b->id] = [
                        'gia'   => PodSo::giaBienThe($m, $b, $bangGia),
                        'phi'   => (int) $m->phi_ca_nhan,
                        'mau'   => $b->dsMau(),
                        'font'  => $m->dsFont(),
                        'goi_y' => ($toiDa <= 0 ? 'Chữ cá nhân hoá (không bắt buộc)'
                            : ($m->laDai() ? 'Bắt buộc: TÊN / NĂM' : ($m->laGiaDinh() ? 'Bắt buộc: Tên 1 / Tên 2 / …' : 'Bắt buộc: tên in, tối đa ' . $toiDa . ' ký tự')))
                            . ($m->phi_ca_nhan ? ' · +' . number_format($m->phi_ca_nhan, 0, ',', '.') . 'đ' : ''),
                    ];
                }
            }
        }
        return view('admin.pod.don-tao', compact('sellers', 's', 'mau', 'noiGiao', 'bt'));
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

        $chon = (string) ($v['noi_giao_id'] ?? '');
        if ($chon !== '' && $chon !== 'moi') {
            $ng = PodNoiGiao::where('seller_id', $s->id)->whereKey((int) $chon)->first();
            if (!$ng) return back()->withErrors(['noi_giao_id' => 'Nơi giao không thuộc seller này.'])->withInput();
            $noiGiao = ['ten' => $ng->ten, 'dia_chi' => $ng->dia_chi, 'sdt' => $ng->sdt, 'ghi_chu' => $ng->ghi_chu];
        } else {
            $ten = trim((string) ($v['ng_ten'] ?? ''));
            $diaChi = trim((string) ($v['ng_dia_chi'] ?? ''));
            if ($ten === '' || $diaChi === '') return back()->withErrors(['noi_giao_id' => 'Chọn nơi giao của seller, hoặc nhập tên + địa chỉ nơi giao mới.'])->withInput();
            $noiGiao = ['ten' => $ten, 'dia_chi' => $diaChi, 'sdt' => trim((string) ($v['ng_sdt'] ?? '')) ?: null, 'ghi_chu' => trim((string) ($v['ng_ghi_chu'] ?? '')) ?: null];
        }

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
        return view('admin.pod.don-show', compact('don', 'fileIn', 'coNhan', 'taoFile', 'tepIn'));
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
