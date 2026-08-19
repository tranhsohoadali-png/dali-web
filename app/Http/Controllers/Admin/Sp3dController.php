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
        $data['anh']  = $this->buildImages($request, []);
        Sp3d::create($data);
        return redirect()->route('admin.sp3d.index')->with('ok', 'Đã thêm sản phẩm 3D!');
    }

    public function update(Request $request, Sp3d $san_pham)
    {
        $data = $this->validated($request);
        $data['slug'] = $this->uniqueSlug($request->input('slug') ?: $request->input('ten'), $san_pham->id);
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

        // Phân loại hàng (kiểu Shopee): biên dịch cấu trúc nhóm+tùy chọn từ form
        // thành mảng phẳng `variants` mà checkout đọc. Thứ tự tổ hợp cố định nên
        // row index == variantIndex — web khách & priceCart không phải sửa gì.
        [$v['variant_groups'], $v['variants']] = $this->compileVariantGroups(
            $request->input('variant_groups_json'),
            (int) $v['gia']
        );

        unset($v['mota_text']);
        return $v;
    }

    /**
     * Biên dịch trạng thái trình sửa (JSON từ form) -> [cấu trúc lưu, mảng variants phẳng].
     * Tổ hợp sinh lại Ở SERVER (không tin thứ tự client), row-major, nhóm 0 vòng ngoài
     * => chỉ số hàng == variantIndex. $base = giá sản phẩm, dùng khi ô giá để trống.
     * @return array{0: array, 1: array}
     */
    private function compileVariantGroups(?string $json, int $base): array
    {
        $raw = json_decode((string) $json, true);
        if (!is_array($raw) || empty($raw['groups'])) {
            return [['groups' => [], 'rows' => []], []]; // không phân loại
        }

        // 1) Làm sạch nhóm + tùy chọn (bỏ dòng trống), tối đa 2 nhóm.
        $groups = [];
        foreach ($raw['groups'] as $g) {
            $opts = [];
            foreach (($g['options'] ?? []) as $o) {
                $o = trim((string) $o);
                if ($o !== '') $opts[] = $o;
            }
            if ($opts) {
                $groups[] = [
                    'ten'     => (trim((string) ($g['ten'] ?? '')) ?: 'Phân loại'),
                    'options' => array_values($opts),
                ];
            }
        }
        $groups = array_slice($groups, 0, 2);
        if (!$groups) return [['groups' => [], 'rows' => []], []];

        // 2) Chỉ mục giá/kho theo khoá tổ hợp (client gửi trong rows).
        $byKey = [];
        foreach (($raw['rows'] ?? []) as $r) {
            $key = implode('-', array_map('intval', $r['combo'] ?? []));
            $kho = (isset($r['kho']) && $r['kho'] !== '' && $r['kho'] !== null) ? (int) $r['kho'] : null;
            $byKey[$key] = ['gia' => (int) ($r['gia'] ?? $base), 'kho' => $kho];
        }

        // 3) Sinh lại danh sách tổ hợp DETERMINISTIC.
        $combos = [[]];
        foreach ($groups as $g) {
            $next = [];
            foreach ($combos as $c) {
                foreach (array_keys($g['options']) as $i) $next[] = array_merge($c, [$i]);
            }
            $combos = $next;
        }

        // 4) Xuất rows (cấu trúc) + variants (mảng phẳng checkout đọc).
        $rows = [];
        $variants = [];
        foreach ($combos as $combo) {
            $label = [];
            foreach ($combo as $gi => $oi) $label[] = $groups[$gi]['options'][$oi];
            $ten = implode(' · ', $label);
            $key = implode('-', $combo);
            $gia = $byKey[$key]['gia'] ?? $base;
            $kho = $byKey[$key]['kho'] ?? null;
            $rows[]     = ['combo' => $combo, 'ten' => $ten, 'gia' => $gia, 'kho' => $kho];
            $variants[] = ['ten' => $ten, 'gia' => $gia]; // giá tuyệt đối cho priceCart
        }
        return [['groups' => $groups, 'rows' => $rows], $variants];
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
