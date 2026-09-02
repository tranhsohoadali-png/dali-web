<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sp3d;
use App\Models\Nhom3d;
use App\Models\DanhMuc3d;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

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
        return view('admin.sp3d.form', ['sp' => null, 'nhoms' => $this->nhomTree()]);
    }

    public function edit(Sp3d $san_pham)
    {
        return view('admin.sp3d.form', ['sp' => $san_pham, 'nhoms' => $this->nhomTree()]);
    }

    private function nhomTree()
    {
        return Nhom3d::with('danhMuc')->orderBy('thu_tu')->orderBy('ten')->get();
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['slug'] = $this->uniqueSlug($request->input('slug') ?: $request->input('ten'));
        [$data['variant_groups'], $data['variants']] = $this->buildVariants($request, null, (int) $data['gia']);
        $data = $this->syncGiaTheoBienThe($data);
        $data['anh']  = $this->buildImages($request, []);
        Sp3d::create($data);
        return redirect()->route('admin.sp3d.index')->with('ok', 'Đã thêm sản phẩm 3D!');
    }

    public function update(Request $request, Sp3d $san_pham)
    {
        $data = $this->validated($request);
        $data['slug'] = $this->uniqueSlug($request->input('slug') ?: $request->input('ten'), $san_pham->id);
        [$data['variant_groups'], $data['variants']] = $this->buildVariants($request, $san_pham, (int) $data['gia']);
        $data = $this->syncGiaTheoBienThe($data);
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

    /**
     * Sản phẩm CÓ phân loại: giá bán = giá RẺ NHẤT trong các phân loại — để thẻ ngoài
     * trang chủ/danh sách hiển thị đúng theo giá phân loại (khỏi sửa "Giá bán" riêng).
     * Bỏ qua bang-tkb (bán qua bộ cấu hình TKB, có giá riêng).
     */
    private function syncGiaTheoBienThe(array $data): array
    {
        if (!empty($data['variants']) && ($data['slug'] ?? '') !== 'bang-tkb') {
            $prices = array_filter(array_map(fn ($v) => (int) ($v['gia'] ?? 0), $data['variants']), fn ($x) => $x > 0);
            if ($prices) $data['gia'] = min($prices);
        }
        return $data;
    }

    /* ---------- helpers ---------- */

    private function validated(Request $request): array
    {
        $v = $request->validate([
            'ten'            => 'required|string|max:200',
            'slug'           => 'nullable|string|max:200',
            'cat'            => 'nullable|string|max:100',
            'danh_muc_id'    => 'nullable|exists:danh_muc_3d,id',
            'nhan'           => 'nullable|string|max:40',
            'mo_ta_ngan'     => 'nullable|string|max:300',
            'mo_ta_dai'      => 'nullable|string|max:3000',
            'gia'            => 'nullable|integer|min:0',
            'gia_si'         => 'nullable|integer|min:0',
            'kho'            => 'nullable|integer|min:0',
            'thu_tu'         => 'nullable|integer',
            'mota_text'      => 'nullable|string',
            'payment_policy' => 'nullable|string|max:40',
            'shipping_class' => 'nullable|string|max:40',
        ]);

        // Mỗi dòng một gạch đầu dòng mô tả
        $v['mota'] = collect(preg_split('/\r?\n/', (string) $request->input('mota_text')))
            ->map(fn ($s) => trim($s))->filter()->values()->all();

        // Biểu tượng dự phòng đã bỏ khỏi form — KHÔNG ghi đè để giữ dữ liệu cũ.
        // Giá gốc bỏ hẳn -> 0. sao/da_ban KHÔNG còn nhập tay: sao mặc định 5.0 ở
        // catalog (giống tranhdali.vn khi chưa có đánh giá), da_ban tự cộng khi
        // Đơn 3D "hoàn tất" — nên KHÔNG set ở đây để không ghi đè.
        $v['khac_ten'] = $request->boolean('khac_ten');
        $v['dat_lam']  = $request->boolean('dat_lam');
        $v['hien']     = $request->boolean('hien', true);
        $v['gia']      = $v['gia'] ?? 0;
        $v['gia_si']   = $v['gia_si'] ?? 0;
        $v['gia_goc']  = 0;
        $v['kho']      = $v['kho'] ?? 0;
        $v['thu_tu']   = $v['thu_tu'] ?? 0;

        // Danh mục: `cat` (chuỗi cũ) suy từ TÊN NHÓM của danh mục để breadcrumb/nhãn cũ vẫn chạy.
        $v['danh_muc_id'] = $v['danh_muc_id'] ?? null;
        if ($v['danh_muc_id']) {
            $dm = DanhMuc3d::with('nhom')->find($v['danh_muc_id']);
            $v['cat'] = $dm && $dm->nhom ? $dm->nhom->ten : ($v['cat'] ?? null);
        }

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
            if ($f && $f->isValid()) {
                $p = $f->store('sp3d', 'public');
                $this->makeThumb($p);
                $newPaths[(int) $k] = $p;
            }
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
                $this->deleteImg($p);
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
        // Lưu ảnh mới -> map chỉ số theo đúng thứ tự client gửi (khớp {new:k} trong anh_order)
        $newPaths = [];
        foreach ((array) $request->file('anh_moi', []) as $k => $f) {
            if ($f && $f->isValid()) {
                $p = $f->store('sp3d', 'public');
                $this->makeThumb($p);
                $newPaths[(int) $k] = $p;
            }
        }

        $order = json_decode($request->input('anh_order', '[]'), true);

        // Dự phòng (JS hỏng / không có order): giữ nguyên ảnh cũ + nối ảnh mới, KHÔNG xoá gì.
        if (!is_array($order) || !$order) {
            return array_values(array_unique(array_merge($cu, array_values($newPaths))));
        }

        // Dựng thứ tự cuối — ảnh mới chèn được vào bất kỳ vị trí (kể cả làm bìa).
        $final = [];
        foreach ($order as $it) {
            if (isset($it['old']) && in_array($it['old'], $cu, true)) {
                $final[] = $it['old'];
            } elseif (isset($it['new']) && isset($newPaths[(int) $it['new']])) {
                $final[] = $newPaths[(int) $it['new']];
            }
        }
        // Ảnh mới lỡ không nằm trong order -> nối cuối, tránh mất ảnh vừa tải.
        foreach ($newPaths as $p) {
            if (!in_array($p, $final, true)) $final[] = $p;
        }

        // Xoá file ảnh cũ không còn giữ.
        foreach (array_diff($cu, $final) as $bo) {
            $this->deleteImg($bo);
        }
        return array_values(array_unique($final));
    }

    /** Đường dẫn ảnh thu nhỏ suy ra từ ảnh lớn: sp3d/abc.png -> sp3d/tn/abc.jpg */
    private function thumbPath(string $big): string
    {
        $dir  = trim(dirname($big), '.');
        $base = pathinfo($big, PATHINFO_FILENAME);
        return ($dir ? $dir . '/' : '') . 'tn/' . $base . '.jpg';
    }

    /** Sinh ảnh thu nhỏ ~400px (JPEG) cạnh ảnh lớn; lỗi thì bỏ qua (front-end fallback ảnh lớn). */
    private function makeThumb(string $big, int $max = 400): void
    {
        try {
            $disk = Storage::disk('public');
            if (!$disk->exists($big)) return;
            $abs  = $disk->path($big);
            $info = @getimagesize($abs);
            if (!$info) return;
            [$w, $h] = $info;
            if ($w < 1 || $h < 1) return;
            $src = match ($info[2]) {
                IMAGETYPE_JPEG => @imagecreatefromjpeg($abs),
                IMAGETYPE_PNG  => @imagecreatefrompng($abs),
                IMAGETYPE_GIF  => @imagecreatefromgif($abs),
                IMAGETYPE_WEBP => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($abs) : null,
                default        => null,
            };
            if (!$src) return;
            $scale = min(1, $max / max($w, $h));
            $nw = max(1, (int) round($w * $scale));
            $nh = max(1, (int) round($h * $scale));
            $dst = imagecreatetruecolor($nw, $nh);
            $white = imagecolorallocate($dst, 255, 255, 255); // nền trắng cho ảnh có alpha
            imagefilledrectangle($dst, 0, 0, $nw, $nh, $white);
            imagecopyresampled($dst, $src, 0, 0, 0, 0, $nw, $nh, $w, $h);
            $tp = $this->thumbPath($big);
            if (!$disk->exists(dirname($tp))) $disk->makeDirectory(dirname($tp));
            imagejpeg($dst, $disk->path($tp), 82);
            imagedestroy($src);
            imagedestroy($dst);
        } catch (\Throwable $e) {
            // im lặng — không chặn việc lưu
        }
    }

    /** Xoá cả ảnh lớn lẫn ảnh thu nhỏ. */
    private function deleteImg(string $big): void
    {
        Storage::disk('public')->delete($big);
        Storage::disk('public')->delete($this->thumbPath($big));
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

    // ─────────────────────────────────────────────────────────────
    //  Trợ lý viết mô tả (Claude nếu có ANTHROPIC_API_KEY, không thì bản mẫu)
    // ─────────────────────────────────────────────────────────────

    /**
     * Nhận tên/danh mục/giá/từ khoá + ảnh (URL đã hosted) -> trả JSON
     * { ok, source, mo_ta_ngan, mota[], mo_ta_dai }. Người dùng xem lại rồi mới lưu.
     */
    public function motaAi(Request $request)
    {
        $v = $request->validate([
            'ten'      => 'nullable|string|max:200',
            'danh_muc' => 'nullable|string|max:120',
            'gia'      => 'nullable|integer|min:0',
            'tu_khoa'  => 'nullable|string|max:2000',
            'khac_ten' => 'nullable|boolean',
            'anh'      => 'nullable|array|max:6',
            'anh.*'    => 'nullable|string|max:400',
        ]);
        $ten     = trim($v['ten'] ?? '');
        $danhMuc = trim($v['danh_muc'] ?? '');
        $gia     = (int) ($v['gia'] ?? 0);
        $tuKhoa  = trim($v['tu_khoa'] ?? '');
        $khacTen = !empty($v['khac_ten']);
        // Chỉ nhận ảnh https đã hosted trong thư mục lưu trữ của mình (tránh SSRF).
        $anh = collect($v['anh'] ?? [])
            ->filter(fn ($u) => is_string($u) && preg_match('#^https?://#', $u) && str_contains($u, '/storage/sp3d/'))
            ->take(3)->values()->all();

        if ($ten === '' && $tuKhoa === '') {
            return response()->json(['ok' => false, 'error' => 'Cần ít nhất tên sản phẩm hoặc vài từ khoá.'], 422);
        }

        $key = config('services.anthropic.key');
        if (!$key) {
            return response()->json(['ok' => true, 'source' => 'template']
                + $this->motaMau($ten, $danhMuc, $gia, $tuKhoa, $khacTen));
        }

        try {
            return response()->json(['ok' => true, 'source' => 'ai']
                + $this->motaClaude($key, $ten, $danhMuc, $gia, $tuKhoa, $khacTen, $anh));
        } catch (\Throwable $e) {
            // API lỗi -> vẫn trả bản mẫu để không chặn người dùng.
            return response()->json([
                'ok' => true, 'source' => 'template',
                'warn' => 'AI chưa gọi được (' . mb_substr($e->getMessage(), 0, 120) . '), tạm dùng bản mẫu.',
            ] + $this->motaMau($ten, $danhMuc, $gia, $tuKhoa, $khacTen));
        }
    }

    /** Gọi Claude qua HTTP (Guzzle của Laravel) — ảnh truyền bằng URL, không base64. */
    private function motaClaude(string $key, string $ten, string $danhMuc, int $gia, string $tuKhoa, bool $khacTen, array $anh): array
    {
        $model = config('services.anthropic.model', 'claude-opus-5');
        $sys = 'Bạn là copywriter của xưởng in 3D DALI 3D (Việt Nam). Viết mô tả sản phẩm bằng tiếng Việt, '
            . 'giọng ấm áp và đáng tin, gợi cảm xúc nhưng TRUNG THỰC — không phóng đại, không hứa điều không có. '
            . 'Văn phong như các shop quà tặng cao cấp: câu gọn, cụ thể, tập trung vào trải nghiệm và lợi ích của người dùng. '
            . 'Chỉ dựa vào thông tin và ảnh được cung cấp; KHÔNG bịa thông số (kích thước, chất liệu) nếu không được cho. '
            . 'Nếu có khắc tên miễn phí thì nhắc tới một cách nhẹ nhàng.';

        $facts = "Thông tin sản phẩm:\n- Tên: {$ten}\n";
        if ($danhMuc) $facts .= "- Danh mục: {$danhMuc}\n";
        if ($gia)     $facts .= '- Giá: ' . number_format($gia, 0, ',', '.') . "đ\n";
        if ($khacTen) $facts .= "- Có khắc tên miễn phí\n";
        if ($tuKhoa)  $facts .= "- Ý chính / từ khoá gợi ý (mỗi dòng một ý):\n{$tuKhoa}\n";
        $facts .= "\nHãy viết (dựa cả vào ảnh nếu có):\n"
            . "1) mo_ta_ngan: MỘT câu (tối đa ~160 ký tự) hiện dưới tên sản phẩm.\n"
            . "2) mota: 4–6 gạch đầu dòng ngắn, mỗi ý một lợi ích/đặc điểm cụ thể.\n"
            . "3) mo_ta_dai: 2–3 đoạn văn (mỗi đoạn 2–4 câu), ngăn nhau bằng một dòng trống.\n"
            . 'CHỈ trả về JSON thuần đúng dạng: '
            . '{"mo_ta_ngan":"...","mota":["...","..."],"mo_ta_dai":"đoạn 1\n\nđoạn 2"} — không thêm chữ nào ngoài JSON.';

        $content = [];
        foreach ($anh as $u) {
            $content[] = ['type' => 'image', 'source' => ['type' => 'url', 'url' => $u]];
        }
        $content[] = ['type' => 'text', 'text' => $facts];

        $resp = Http::withHeaders([
            'x-api-key'         => $key,
            'anthropic-version' => '2023-06-01',
        ])->timeout(60)->post('https://api.anthropic.com/v1/messages', [
            'model'      => $model,
            'max_tokens' => 1500,
            'system'     => $sys,
            'messages'   => [['role' => 'user', 'content' => $content]],
        ]);

        if (!$resp->successful()) {
            throw new \RuntimeException('HTTP ' . $resp->status() . ' ' . mb_substr($resp->body(), 0, 160));
        }
        $text = collect($resp->json('content') ?: [])
            ->where('type', 'text')->pluck('text')->implode("\n");
        $json = $this->jsonTuText($text);
        if (!$json) throw new \RuntimeException('Không đọc được JSON từ phản hồi AI.');

        return [
            'mo_ta_ngan' => trim((string) ($json['mo_ta_ngan'] ?? '')),
            'mota'       => array_values(array_filter(array_map(fn ($x) => trim((string) $x), (array) ($json['mota'] ?? [])))),
            'mo_ta_dai'  => trim((string) ($json['mo_ta_dai'] ?? '')),
        ];
    }

    /** Rút mảng JSON từ text trả về (bỏ hàng rào ```json và chữ thừa quanh JSON). */
    private function jsonTuText(string $text): ?array
    {
        $t = trim($text);
        $t = preg_replace('/```(?:json)?/i', '', $t);
        $s = strpos($t, '{');
        $e = strrpos($t, '}');
        if ($s === false || $e === false || $e < $s) return null;
        $d = json_decode(substr($t, $s, $e - $s + 1), true);
        return is_array($d) ? $d : null;
    }

    /** Bản mẫu (không AI): ghép khung mô tả theo văn phong DALI từ dữ liệu đã nhập. */
    private function motaMau(string $ten, string $danhMuc, int $gia, string $tuKhoa, bool $khacTen): array
    {
        $ten = $ten !== '' ? $ten : 'Sản phẩm';
        $kw  = collect(preg_split('/[\r\n]+/', $tuKhoa))
            ->map(fn ($x) => trim($x))->filter()->values()->all();

        $mota = array_map(fn ($k) => Str::ucfirst($k), $kw);
        if ($khacTen) $mota[] = 'Khắc tên miễn phí trước khi in';
        if (!$mota)   $mota = ['Thiết kế in 3D tỉ mỉ, chắc chắn', 'Màu sắc tươi, an toàn khi sử dụng', 'Phù hợp làm quà tặng hoặc trang trí'];

        $short = $khacTen
            ? "{$ten} — in 3D theo yêu cầu tại DALI 3D, khắc tên miễn phí."
            : "{$ten} — in 3D theo yêu cầu tại DALI 3D.";

        $p1 = "{$ten} được xưởng DALI 3D in theo yêu cầu, chăm chút từng chi tiết để bạn nhận đúng món mình hình dung.";
        $p2 = $kw
            ? ('Điểm nổi bật: ' . implode('; ', array_slice($kw, 0, 4)) . '.')
            : 'Sản phẩm hoàn thiện gọn gàng, chắc chắn, phù hợp làm quà tặng hoặc trang trí góc nhỏ của bạn.';
        $p3 = ($khacTen ? 'Xưởng khắc tên miễn phí trước khi in. ' : '') . 'Đặt hàng dễ dàng, giao toàn quốc.';

        return [
            'mo_ta_ngan' => $short,
            'mota'       => array_values($mota),
            'mo_ta_dai'  => $p1 . "\n\n" . $p2 . "\n\n" . $p3,
        ];
    }
}
