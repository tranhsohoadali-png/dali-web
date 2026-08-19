<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sp3d;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

/**
 * Quản lý sản phẩm khu "Xưởng in 3D".
 * Màn riêng, dữ liệu riêng (bảng sp_3d) — không đụng tới sản phẩm tranh.
 * Ảnh lưu ở disk public, thư mục sp3d/.
 */
class Sp3dController extends Controller
{
    public function index(Request $request)
    {
        $q = Sp3d::orderBy('thu_tu')->orderBy('created_at', 'desc');
        if ($request->filled('search')) {
            $q->where('ten', 'like', '%' . $request->search . '%');
        }
        $items = $q->paginate(20)->withQueryString();
        return view('admin.sp3d.index', compact('items'));
    }

    public function create()
    {
        return view('admin.sp3d.form', ['sp' => null]);
    }

    public function edit(Sp3d $san_pham)
    {
        return view('admin.sp3d.form', ['sp' => $san_pham]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['slug'] = $this->uniqueSlug($request->input('slug') ?: $request->input('ten'));
        [$data['variant_groups'], $data['variants']] = $this->buildVariants($request, null, (int) $data['gia']);
        $data['anh']  = $this->buildImages($request, []);
        Sp3d::create($data);
        return redirect()->route('admin.sp3d.index')->with('ok', 'Đã thêm sản phẩm 3D!');
    }

    public function update(Request $request, Sp3d $san_pham)
    {
        $data = $this->validated($request);
        $data['slug'] = $this->uniqueSlug($request->input('slug') ?: $request->input('ten'), $san_pham->id);
        [$data['variant_groups'], $data['variants']] = $this->buildVariants($request, $san_pham, (int) $data['gia']);
        $data['anh']  = $this->buildImages($request, $san_pham->anh ?: []);
        $san_pham->update($data);
        return redirect()->route('admin.sp3d.index')->with('ok', 'Đã cập nhật sản phẩm!');
    }

    public function destroy(Sp3d $san_pham)
    {
        foreach (($san_pham->anh ?: []) as $img) {
            Storage::disk('public')->delete($img);
        }
        $san_pham->delete();
        return back()->with('ok', 'Đã xoá sản phẩm!');
    }

    /* ---------- helpers ---------- */

    private function validated(Request $request): array
    {
        $v = $request->validate([
            'ten'            => 'required|string|max:200',
            'slug'           => 'nullable|string|max:200',
            'cat'            => 'nullable|string|max:100',
            'nhan'           => 'nullable|string|max:40',
            'mo_ta_ngan'     => 'nullable|string|max:300',
            'gia'            => 'nullable|integer|min:0',
            'kho'            => 'nullable|integer|min:0',
            'thu_tu'         => 'nullable|integer',
            'sao'            => 'nullable|numeric|min:0|max:5',
            'da_ban'         => 'nullable|integer|min:0',
            'mota_text'      => 'nullable|string',
            'payment_policy' => 'nullable|string|max:40',
            'shipping_class' => 'nullable|string|max:40',
        ]);

        // Mỗi dòng một gạch đầu dòng mô tả
        $v['mota'] = collect(preg_split('/\r?\n/', (string) $request->input('mota_text')))
            ->map(fn ($s) => trim($s))->filter()->values()->all();

        // Biểu tượng dự phòng đã bỏ khỏi form — KHÔNG ghi đè để giữ dữ liệu cũ.
        // Giá gốc bỏ hẳn -> 0.
        $v['khac_ten'] = $request->boolean('khac_ten');
        $v['dat_lam']  = $request->boolean('dat_lam');
        $v['hien']     = $request->boolean('hien', true);
        $v['gia']      = $v['gia'] ?? 0;
        $v['gia_goc']  = 0;
        $v['sao']      = $v['sao'] ?? 0;
        $v['da_ban']   = $v['da_ban'] ?? 0;
        $v['kho']      = $v['kho'] ?? 0;
        $v['thu_tu']   = $v['thu_tu'] ?? 0;

        unset($v['mota_text']);
        return $v;
    }

    /**
     * Biên dịch trình sửa phân loại (JSON + ảnh tải lên) -> [cấu trúc lưu, mảng variants phẳng].
     * - Tổ hợp sinh lại Ở SERVER (không tin thứ tự client), row-major, nhóm 0 vòng ngoài
     *   => chỉ số hàng == variantIndex (web khách & priceCart không phải sửa).
     * - Ảnh: mỗi lựa chọn của NHÓM 1 có 1 ảnh; ghi vào groups[0].imgs và vào từng
     *   variant phẳng dưới khoá 'anh' (priceCart bỏ qua khoá thừa này).
     * @return array{0: array, 1: array}
     */
    private function buildVariants(Request $request, ?Sp3d $sp, int $base): array
    {
        $raw = json_decode((string) $request->input('variant_groups_json'), true);
        $oldImgs = $this->oldVariantImgs($sp);

        if (!is_array($raw) || empty($raw['groups'])) {
            $this->deleteVariantImgs($oldImgs, [], $sp);
            return [['groups' => [], 'rows' => []], []];
        }

        // 1) Lưu ảnh mới tải lên (variant_img_new[]) -> map chỉ số -> đường dẫn.
        $newPaths = [];
        foreach ((array) $request->file('variant_img_new', []) as $k => $f) {
            if ($f && $f->isValid()) $newPaths[(int) $k] = $f->store('sp3d', 'public');
        }

        // 2) Làm sạch nhóm; nhóm 0 giữ ảnh song song với lựa chọn.
        $groups = [];
        foreach ($raw['groups'] as $gi => $g) {
            $opts = [];
            $imgsRaw = [];
            $srcImgs = $g['imgs'] ?? [];
            foreach (($g['options'] ?? []) as $oi => $o) {
                $o = trim((string) $o);
                if ($o === '') continue;
                $opts[] = $o;
                if ((int) $gi === 0) $imgsRaw[] = $srcImgs[$oi] ?? null;
            }
            if ($opts) {
                $grp = ['ten' => (trim((string) ($g['ten'] ?? '')) ?: 'Phân loại'), 'options' => array_values($opts)];
                if ((int) $gi === 0) $grp['_imgsRaw'] = $imgsRaw;
                $groups[] = $grp;
            }
        }
        $groups = array_slice($groups, 0, 2);
        if (!$groups) {
            $this->deleteVariantImgs($oldImgs, [], $sp);
            return [['groups' => [], 'rows' => []], []];
        }

        // 3) Giải ảnh nhóm 0: ưu tiên ảnh mới, rồi ảnh cũ hợp lệ, còn lại null.
        $g0imgs = [];
        foreach (($groups[0]['_imgsRaw'] ?? []) as $im) {
            if (is_array($im) && isset($im['new']) && isset($newPaths[(int) $im['new']])) {
                $g0imgs[] = $newPaths[(int) $im['new']];
            } elseif (is_array($im) && !empty($im['path']) && in_array($im['path'], $oldImgs, true)) {
                $g0imgs[] = $im['path'];
            } elseif (is_string($im) && $im !== '' && in_array($im, $oldImgs, true)) {
                $g0imgs[] = $im;
            } else {
                $g0imgs[] = null;
            }
        }
        unset($groups[0]['_imgsRaw']);
        $n0 = count($groups[0]['options']);
        $g0imgs = array_slice(array_pad($g0imgs, $n0, null), 0, $n0);
        $groups[0]['imgs'] = $g0imgs;

        // 4) Xoá ảnh biến thể cũ không còn dùng.
        $keep = array_values(array_filter($g0imgs, fn ($p) => is_string($p) && $p !== ''));
        $this->deleteVariantImgs($oldImgs, $keep, $sp);

        // 5) Giá/kho theo khoá tổ hợp.
        $byKey = [];
        foreach (($raw['rows'] ?? []) as $r) {
            $key = implode('-', array_map('intval', $r['combo'] ?? []));
            $kho = (isset($r['kho']) && $r['kho'] !== '' && $r['kho'] !== null) ? (int) $r['kho'] : null;
            $byKey[$key] = ['gia' => (int) ($r['gia'] ?? $base), 'kho' => $kho];
        }

        // 6) Sinh tổ hợp DETERMINISTIC + variants phẳng (kèm 'anh' theo nhóm 0).
        $combos = [[]];
        foreach ($groups as $g) {
            $next = [];
            foreach ($combos as $c) {
                foreach (array_keys($g['options']) as $i) $next[] = array_merge($c, [$i]);
            }
            $combos = $next;
        }
        $rows = [];
        $variants = [];
        foreach ($combos as $combo) {
            $label = [];
            foreach ($combo as $gi => $oi) $label[] = $groups[$gi]['options'][$oi];
            $ten = implode(' · ', $label);
            $key = implode('-', $combo);
            $gia = $byKey[$key]['gia'] ?? $base;
            $kho = $byKey[$key]['kho'] ?? null;
            $anh = $g0imgs[$combo[0]] ?? null;
            $rows[] = ['combo' => $combo, 'ten' => $ten, 'gia' => $gia, 'kho' => $kho];
            $v = ['ten' => $ten, 'gia' => $gia];
            if ($anh) $v['anh'] = $anh;
            $variants[] = $v;
        }
        return [['groups' => $groups, 'rows' => $rows], $variants];
    }

    /** Danh sách đường dẫn ảnh biến thể đã lưu của sản phẩm (để giữ/xoá). */
    private function oldVariantImgs(?Sp3d $sp): array
    {
        $imgs = ($sp && is_array($sp->variant_groups)) ? ($sp->variant_groups['groups'][0]['imgs'] ?? []) : [];
        return array_values(array_filter((array) $imgs, fn ($p) => is_string($p) && $p !== ''));
    }

    /** Xoá file ảnh biến thể cũ không còn dùng (không đụng ảnh trong bộ ảnh chính). */
    private function deleteVariantImgs(array $old, array $keep, ?Sp3d $sp): void
    {
        $gallery = $sp ? ($sp->anh ?: []) : [];
        foreach ($old as $p) {
            if (!in_array($p, $keep, true) && !in_array($p, $gallery, true)) {
                Storage::disk('public')->delete($p);
            }
        }
    }

    /**
     * Ghép danh sách ảnh cuối cùng:
     * anh_keep = các ảnh cũ được giữ (đúng thứ tự người dùng sắp), rồi nối ảnh mới tải lên.
     * Ảnh cũ bị bỏ khỏi anh_keep coi như xoá — xoá luôn file.
     */
    private function buildImages(Request $request, array $cu): array
    {
        $keep = collect(json_decode($request->input('anh_keep', '[]'), true) ?: [])
            ->filter(fn ($p) => in_array($p, $cu))->values();

        // ảnh cũ không còn trong keep -> xoá file
        foreach (array_diff($cu, $keep->all()) as $bo) {
            Storage::disk('public')->delete($bo);
        }

        // ảnh mới tải lên
        if ($request->hasFile('anh_moi')) {
            foreach ($request->file('anh_moi') as $file) {
                if ($file && $file->isValid()) {
                    $keep->push($file->store('sp3d', 'public'));
                }
            }
        }
        return $keep->values()->all();
    }

    private function uniqueSlug(string $raw, ?int $ignoreId = null): string
    {
        $base = Str::slug($raw) ?: 'sp-3d';
        $slug = $base;
        $i = 1;
        while (Sp3d::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = $base . '-' . (++$i);
        }
        return $slug;
    }
}
