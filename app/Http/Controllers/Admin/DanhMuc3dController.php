<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Nhom3d;
use App\Models\DanhMuc3d;
use App\Models\Sp3d;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/** Quản lý cây danh mục Xưởng in 3D: Nhóm -> Danh mục. Một trang, CRUD cả hai cấp. */
class DanhMuc3dController extends Controller
{
    public function index()
    {
        $nhoms  = Nhom3d::with('danhMuc')->orderBy('thu_tu')->orderBy('ten')->get();
        $counts = Sp3d::selectRaw('danh_muc_id, count(*) c')->groupBy('danh_muc_id')->pluck('c', 'danh_muc_id');
        return view('admin.danhmuc3d.index', compact('nhoms', 'counts'));
    }

    /* ---------- Nhóm ---------- */

    public function storeNhom(Request $request)
    {
        $v = $request->validate(['ten' => 'required|string|max:100', 'mo_ta' => 'nullable|string|max:200', 'thu_tu' => 'nullable|integer']);
        Nhom3d::create([
            'ten'    => $v['ten'],
            'slug'   => $this->uniqueSlug(Nhom3d::class, $v['ten']),
            'mo_ta'  => $v['mo_ta'] ?? null,
            'thu_tu' => $v['thu_tu'] ?? 0,
            'hien'   => true,
        ]);
        return back()->with('ok', 'Đã thêm nhóm “' . $v['ten'] . '”');
    }

    public function updateNhom(Request $request, Nhom3d $nhom)
    {
        $v = $request->validate(['ten' => 'required|string|max:100', 'mo_ta' => 'nullable|string|max:200', 'thu_tu' => 'nullable|integer']);
        $nhom->update([
            'ten'    => $v['ten'],
            'mo_ta'  => $v['mo_ta'] ?? null,
            'thu_tu' => $v['thu_tu'] ?? 0,
            'hien'   => $request->boolean('hien'),
        ]);
        return back()->with('ok', 'Đã lưu nhóm');
    }

    public function destroyNhom(Nhom3d $nhom)
    {
        // Gỡ sản phẩm khỏi các danh mục của nhóm (về “chưa phân loại”) trước khi cascade xoá danh mục.
        Sp3d::whereIn('danh_muc_id', $nhom->danhMuc()->pluck('id'))->update(['danh_muc_id' => null]);
        $ten = $nhom->ten;
        $nhom->delete(); // cascade xoá danh_muc_3d
        return back()->with('ok', 'Đã xoá nhóm “' . $ten . '” và các danh mục con');
    }

    /* ---------- Danh mục ---------- */

    public function storeDanhMuc(Request $request)
    {
        $v = $request->validate([
            'nhom_id' => 'required|exists:nhom_3d,id',
            'ten'     => 'required|string|max:100',
            'icon'    => 'nullable|string|max:8',
            'thu_tu'  => 'nullable|integer',
        ]);
        DanhMuc3d::create([
            'nhom_id' => $v['nhom_id'],
            'ten'     => $v['ten'],
            'slug'    => $this->uniqueSlug(DanhMuc3d::class, $v['ten']),
            'icon'    => $v['icon'] ?? null,
            'thu_tu'  => $v['thu_tu'] ?? 0,
            'hien'    => true,
        ]);
        return back()->with('ok', 'Đã thêm danh mục “' . $v['ten'] . '”');
    }

    public function updateDanhMuc(Request $request, DanhMuc3d $muc)
    {
        $v = $request->validate([
            'nhom_id' => 'required|exists:nhom_3d,id',
            'ten'     => 'required|string|max:100',
            'icon'    => 'nullable|string|max:8',
            'thu_tu'  => 'nullable|integer',
        ]);
        $muc->update([
            'nhom_id' => $v['nhom_id'],
            'ten'     => $v['ten'],
            'icon'    => $v['icon'] ?? null,
            'thu_tu'  => $v['thu_tu'] ?? 0,
            'hien'    => $request->boolean('hien'),
        ]);
        return back()->with('ok', 'Đã lưu danh mục');
    }

    public function destroyDanhMuc(DanhMuc3d $muc)
    {
        Sp3d::where('danh_muc_id', $muc->id)->update(['danh_muc_id' => null]);
        $ten = $muc->ten;
        $muc->delete();
        return back()->with('ok', 'Đã xoá danh mục “' . $ten . '”');
    }

    /* ---------- helper ---------- */

    private function uniqueSlug(string $model, string $raw): string
    {
        $base = Str::slug($raw) ?: 'muc';
        $slug = $base;
        $i = 1;
        while ($model::where('slug', $slug)->exists()) $slug = $base . '-' . (++$i);
        return $slug;
    }
}
