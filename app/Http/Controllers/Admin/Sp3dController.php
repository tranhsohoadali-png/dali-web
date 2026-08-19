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
            'art'            => 'nullable|string|max:16',
            'mo_ta_ngan'     => 'nullable|string|max:300',
            'gia'            => 'nullable|integer|min:0',
            'gia_goc'        => 'nullable|integer|min:0',
            'kho'            => 'nullable|integer|min:0',
            'thu_tu'         => 'nullable|integer',
            'sao'            => 'nullable|numeric|min:0|max:5',
            'da_ban'         => 'nullable|integer|min:0',
            'mota_text'      => 'nullable|string',
            'variants_text'  => 'nullable|string',
            'payment_policy' => 'nullable|string|max:40',
            'shipping_class' => 'nullable|string|max:40',
        ]);

        // Mỗi dòng một gạch đầu dòng mô tả
        $v['mota'] = collect(preg_split('/\r?\n/', (string) $request->input('mota_text')))
            ->map(fn ($s) => trim($s))->filter()->values()->all();

        // Phân loại: mỗi dòng "Tên | Giá"
        $v['variants'] = collect(preg_split('/\r?\n/', (string) $request->input('variants_text')))
            ->map(fn ($s) => trim($s))->filter()
            ->map(function ($line) {
                $p = explode('|', $line);
                return ['ten' => trim($p[0] ?? ''), 'gia' => (int) preg_replace('/\D/', '', $p[1] ?? '0')];
            })->values()->all();

        $v['khac_ten'] = $request->boolean('khac_ten');
        $v['dat_lam']  = $request->boolean('dat_lam');
        $v['hien']     = $request->boolean('hien', true);
        $v['gia']      = $v['gia'] ?? 0;
        $v['gia_goc']  = $v['gia_goc'] ?? 0;
        $v['sao']      = $v['sao'] ?? 0;
        $v['da_ban']   = $v['da_ban'] ?? 0;
        $v['kho']      = $v['kho'] ?? 0;
        $v['thu_tu']   = $v['thu_tu'] ?? 0;

        unset($v['mota_text'], $v['variants_text']);
        return $v;
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
