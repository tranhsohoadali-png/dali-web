<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PodBienThe;
use App\Models\PodMau;
use App\Models\PodNhatKy;
use App\Models\PodSeller;
use App\Services\PodSo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Cổng POD: mẫu (Kho chung + Kho riêng từng seller), phân loại + gram, ảnh (đĩa private).
 * Mẫu riêng KHÔNG BAO GIỜ tự sang Kho chung: phải tích xác nhận + ghi lời đồng ý của seller, và được ghi nhật ký.
 */
class PodMauController extends Controller
{
    public function index(Request $request)
    {
        $kho = (string) $request->query('kho', '');
        $q = PodMau::with(['bienThe', 'chu'])->orderByDesc('id');
        if ($kho === 'chung') $q->whereNull('chu_seller_id');
        elseif ($kho === 'rieng') $q->whereNotNull('chu_seller_id');
        elseif (ctype_digit($kho)) $q->where('chu_seller_id', (int) $kho);
        $items = $q->get();
        $sellers = PodSeller::orderBy('ten')->get(['id', 'ten', 'ma']);
        $giaGram = PodSo::giaGram();
        return view('admin.pod.mau-index', compact('items', 'sellers', 'kho', 'giaGram'));
    }

    public function create()
    {
        $mau = new PodMau(['hien' => true]);
        $sellers = PodSeller::orderBy('ten')->get(['id', 'ten', 'ma']);
        return view('admin.pod.mau-form', ['mau' => $mau, 'sellers' => $sellers, 'bienThe' => collect()]);
    }

    public function store(Request $request)
    {
        $v = $this->kiem($request);
        $mau = DB::transaction(function () use ($v, $request) {
            $mau = PodMau::create([
                'ma' => PodMau::taoMa(), 'ten' => $v['ten'], 'nhom' => $v['nhom'] ?? null, 'mo_ta' => $v['mo_ta'] ?? null,
                'chu_seller_id' => $v['chu_seller_id'] ?? null, 'phi_ca_nhan' => (int) ($v['phi_ca_nhan'] ?? 0),
                'phi_thiet_ke' => (int) ($v['phi_thiet_ke'] ?? 0), 'hien' => $request->boolean('hien'), 'anh' => [],
            ]);
            $this->luuBienThe($mau, $v['bt'] ?? []);
            $this->luuAnh($mau, $request);
            return $mau;
        });
        PodNhatKy::ghi($mau->id, 'tao', $mau->laRieng() ? 'Kho riêng của ' . $this->tenSeller($mau->chu_seller_id) : 'Kho chung');
        return redirect()->route('admin.pod.mau.edit', $mau)->with('ok', 'Đã tạo mẫu "' . $mau->ten . '".');
    }

    public function edit(PodMau $mau)
    {
        if ($mau->laRieng()) PodNhatKy::ghi($mau->id, 'xem', 'Mở trang sửa mẫu riêng');
        $sellers = PodSeller::orderBy('ten')->get(['id', 'ten', 'ma']);
        return view('admin.pod.mau-form', ['mau' => $mau, 'sellers' => $sellers, 'bienThe' => $mau->bienThe()->get()]);
    }

    public function update(Request $request, PodMau $mau)
    {
        $v = $this->kiem($request);
        $chuCu = $mau->chu_seller_id;
        $chuMoi = $v['chu_seller_id'] ?? null;
        $sangChung = $chuCu !== null && $chuMoi === null;
        if ($sangChung) {
            $dongY = trim((string) $request->input('dong_y_chung', ''));
            if (!$request->boolean('xac_nhan_chung') || $dongY === '') {
                return back()->withErrors(['chu_seller_id' => 'Chuyển mẫu riêng sang Kho chung cần tích xác nhận và ghi lời đồng ý của seller.'])->withInput();
            }
        }
        DB::transaction(function () use ($mau, $v, $request, $chuMoi) {
            $mau->update([
                'ten' => $v['ten'], 'nhom' => $v['nhom'] ?? null, 'mo_ta' => $v['mo_ta'] ?? null,
                'chu_seller_id' => $chuMoi, 'phi_ca_nhan' => (int) ($v['phi_ca_nhan'] ?? 0),
                'phi_thiet_ke' => (int) ($v['phi_thiet_ke'] ?? 0), 'hien' => $request->boolean('hien'),
            ]);
            $this->luuBienThe($mau, $v['bt'] ?? []);
            $this->luuAnh($mau, $request);
        });
        if ($sangChung) {
            PodNhatKy::ghi($mau->id, 'chuyen_kho_chung', 'Từ Kho riêng của ' . $this->tenSeller($chuCu) . '. Đồng ý: ' . trim((string) $request->input('dong_y_chung')));
        } elseif ($chuCu !== $chuMoi) {
            PodNhatKy::ghi($mau->id, 'doi_chu', ($chuCu ? $this->tenSeller($chuCu) : 'Kho chung') . ' → ' . ($chuMoi ? $this->tenSeller($chuMoi) : 'Kho chung'));
        } elseif ($mau->laRieng()) {
            PodNhatKy::ghi($mau->id, 'sua', 'Sửa mẫu riêng');
        }
        return back()->with('ok', 'Đã lưu mẫu.');
    }

    /** Ảnh mẫu cho admin (đĩa private). */
    public function anh(PodMau $mau, int $i)
    {
        $rel = array_values((array) ($mau->anh ?: []))[$i] ?? null;
        if (!$rel || !Storage::disk('local')->exists($rel)) abort(404);
        return response()->file(Storage::disk('local')->path($rel), ['Cache-Control' => 'private, max-age=600']);
    }

    public function giaGram(Request $request)
    {
        $v = $request->validate(['gia_gram' => 'required|integer|min:100|max:100000']);
        PodSo::datGiaGram((int) $v['gia_gram']);
        return back()->with('ok', 'Đơn giá mới: ' . number_format($v['gia_gram'], 0, ',', '.') . 'đ/gram. Đơn đã đặt giữ giá cũ.');
    }

    public function nhatKy()
    {
        $dong = PodNhatKy::orderByDesc('id')->paginate(100);
        $mau = PodMau::whereIn('id', $dong->pluck('mau_id')->filter()->unique())->get(['id', 'ma', 'ten'])->keyBy('id');
        return view('admin.pod.nhatky', compact('dong', 'mau'));
    }

    private function kiem(Request $request): array
    {
        $v = $request->validate([
            'ten'            => 'required|string|max:160',
            'nhom'           => 'nullable|string|max:60',
            'mo_ta'          => 'nullable|string|max:5000',
            'chu_seller_id'  => 'nullable|integer|exists:pod_seller,id',
            'phi_ca_nhan'    => 'nullable|integer|min:0|max:10000',
            'phi_thiet_ke'   => 'nullable|integer|min:0|max:100000000',
            'bt'             => 'nullable|array|max:40',
            'bt.*.id'        => 'nullable|integer',
            'bt.*.ten'       => 'nullable|string|max:120',
            'bt.*.gram'      => 'nullable|numeric|min:0.1|max:5000',
            'bt.*.mau_sac'   => 'nullable|string|max:300',
            'bt.*.file_in'   => 'nullable|string|max:300',
            'anh_moi'        => 'nullable|array|max:12',
            'anh_moi.*'      => 'image|mimes:jpg,jpeg,png,webp|max:8192',
            'xoa_anh'        => 'nullable|array',
        ]);
        $v['chu_seller_id'] = isset($v['chu_seller_id']) && $v['chu_seller_id'] !== '' ? (int) $v['chu_seller_id'] : null;
        foreach ($v['bt'] ?? [] as $i => $b) {
            if (trim((string) ($b['ten'] ?? '')) !== '' && empty($b['gram'])) {
                abort(back()->withErrors(["bt.$i.gram" => 'Phân loại "' . $b['ten'] . '" chưa có số gram.'])->withInput());
            }
        }
        return $v;
    }

    /** Dòng có tên = giữ/cập nhật; dòng cũ bỏ trống tên = xoá. */
    private function luuBienThe(PodMau $mau, array $rows): void
    {
        $giu = [];
        $thuTu = 0;
        foreach ($rows as $b) {
            $ten = trim((string) ($b['ten'] ?? ''));
            if ($ten === '') continue;
            $data = [
                'ten' => $ten, 'gram' => round((float) $b['gram'], 1),
                'mau_sac' => trim((string) ($b['mau_sac'] ?? '')) ?: null,
                'file_in' => trim((string) ($b['file_in'] ?? '')) ?: null,
                'thu_tu' => $thuTu++,
            ];
            $bt = !empty($b['id']) ? PodBienThe::where('mau_id', $mau->id)->whereKey((int) $b['id'])->first() : null;
            if ($bt) $bt->update($data);
            else $bt = PodBienThe::create($data + ['mau_id' => $mau->id]);
            $giu[] = $bt->id;
        }
        PodBienThe::where('mau_id', $mau->id)->whereNotIn('id', $giu ?: [0])->delete();
    }

    private function luuAnh(PodMau $mau, Request $request): void
    {
        $anh = array_values((array) ($mau->anh ?: []));
        $xoa = array_map('intval', (array) $request->input('xoa_anh', []));
        foreach ($xoa as $i) {
            if (isset($anh[$i])) { Storage::disk('local')->delete($anh[$i]); unset($anh[$i]); }
        }
        $anh = array_values($anh);
        foreach ((array) $request->file('anh_moi', []) as $f) {
            $ext = strtolower($f->getClientOriginalExtension() ?: 'jpg');
            $anh[] = $f->storeAs('pod/anh', $mau->ma . '-' . bin2hex(random_bytes(4)) . '.' . $ext, 'local');
        }
        $mau->update(['anh' => $anh]);
    }

    private function tenSeller(?int $id): string
    {
        $s = $id ? PodSeller::find($id) : null;
        return $s ? $s->ten . ' (' . $s->ma . ')' : 'seller #' . $id;
    }
}
