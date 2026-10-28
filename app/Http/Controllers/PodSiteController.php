<?php
namespace App\Http\Controllers;

use App\Models\PodMau;
use App\Models\PodSeller;
use App\Services\PodSo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Storage;

/**
 * Web công khai cho seller ở pod.tranhdali.vn: trang chủ, bảng giá, kho mẫu chung, đăng ký.
 * Google được lập chỉ mục các trang này. Cổng sau đăng nhập (/cong) và thợ in (/tho-in) vẫn noindex.
 *
 * CHỈ MẪU KHO CHUNG (chu_seller_id NULL) được hiện ở đây — qua scope khoChung().
 * Giá từng mẫu (gram) KHÔNG công khai: chỉ thấy sau khi đăng nhập.
 */
class PodSiteController extends Controller
{
    /** Mẫu công khai: Kho chung, đang hiện. Mẫu riêng của seller không bao giờ qua đây. */
    private function khoChung()
    {
        return PodMau::whereNull('chu_seller_id')->where('hien', true);
    }

    public function trangChu()
    {
        $noiBat = $this->khoChung()->orderByDesc('id')->limit(8)->get()->filter(fn ($m) => !empty($m->anh))->values();
        return view('pod.site.trang-chu', ['noiBat' => $noiBat, 'giaGram' => PodSo::giaGram(), 'soMau' => $this->khoChung()->count()]);
    }

    public function bangGia()
    {
        $gia = PodSo::giaGram();
        // Ví dụ tham khảo lấy đúng từ bảng giá in 3D PLA (10/2026); tiền tính theo đơn giá hiện hành
        $viDu = collect([
            ['Móc khoá tên 2 màu', 15], ['Biển tên / logo để bàn', 50], ['Lithophane / khung ảnh', 80],
            ['Tượng chibi 10 cm nhiều màu', 120], ['Đèn / bình trang trí', 300],
        ])->map(fn ($r) => ['ten' => $r[0], 'gram' => $r[1], 'gia' => $r[1] * $gia]);
        return view('pod.site.bang-gia', compact('gia', 'viDu'));
    }

    public function khoMau(Request $request)
    {
        $nhom = trim((string) $request->query('nhom', ''));
        $q = $this->khoChung()->with('bienThe')->orderByDesc('id');
        if ($nhom !== '') $q->where('nhom', $nhom);
        $ds = $q->get();
        $cacNhom = $this->khoChung()->whereNotNull('nhom')->where('nhom', '!=', '')->distinct()->orderBy('nhom')->pluck('nhom');
        return view('pod.site.kho-mau', compact('ds', 'cacNhom', 'nhom'));
    }

    public function mau(string $ma)
    {
        $m = $this->khoChung()->with('bienThe')->where('ma', $ma)->first();
        if (!$m) abort(404);
        $lienQuan = $this->khoChung()->where('id', '!=', $m->id)->when($m->nhom, fn ($q) => $q->where('nhom', $m->nhom))
            ->orderByDesc('id')->limit(4)->get()->filter(fn ($x) => !empty($x->anh))->values();
        return view('pod.site.mau', compact('m', 'lienQuan'));
    }

    /** Ảnh mẫu KHO CHUNG cho khách chưa đăng nhập. Mẫu riêng hoặc đang ẩn -> 404. */
    public function anh(string $ma, int $i)
    {
        $m = $this->khoChung()->where('ma', $ma)->first();
        $rel = $m ? (array_values((array) ($m->anh ?: []))[$i] ?? null) : null;
        if (!$rel || !Storage::disk('local')->exists($rel)) abort(404);
        return response()->file(Storage::disk('local')->path($rel), ['Cache-Control' => 'public, max-age=86400']);
    }

    public function dangKyForm()
    {
        return view('pod.site.dang-ky');
    }

    /** Seller tự đăng ký -> tài khoản CHỜ DUYỆT; xưởng duyệt trong admin rồi mới đăng nhập được. */
    public function dangKy(Request $request)
    {
        if (trim((string) $request->input('website', '')) !== '') { // ô bẫy máy spam, người thật không thấy
            return redirect()->route('pod.site.dang-ky')->with('xong', true);
        }
        $key = 'pod-dang-ky:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            return back()->withErrors(['sdt' => 'Bạn gửi nhiều lần quá, thử lại sau ít phút hoặc nhắn Zalo cho xưởng.'])->withInput();
        }
        RateLimiter::hit($key, 3600);
        $v = $request->validate([
            'ten'       => 'required|string|max:120',
            'sdt'       => 'required|string|max:20',
            'matkhau'   => 'required|string|min:6|max:100|confirmed',
            'san_ban'   => 'nullable|string|max:120',
            'link_shop' => 'nullable|url|max:300',
            'loi_nhan'  => 'nullable|string|max:1000',
            'dong_y'    => 'accepted',
        ], [
            'ten.required'       => 'Nhập tên shop hoặc tên bạn.',
            'sdt.required'       => 'Nhập số điện thoại (Zalo).',
            'matkhau.min'        => 'Mật khẩu ít nhất 6 ký tự.',
            'matkhau.confirmed'  => 'Hai lần nhập mật khẩu không khớp.',
            'link_shop.url'      => 'Link shop phải bắt đầu bằng https://',
            'dong_y.accepted'    => 'Cần đồng ý điều khoản bảo mật thiết kế.',
        ]);
        $sdt = preg_replace('/[^0-9+]/', '', $v['sdt']);
        if (strlen($sdt) < 9) return back()->withErrors(['sdt' => 'Số điện thoại chưa đúng.'])->withInput();
        if (PodSeller::where('sdt', $sdt)->exists()) {
            return back()->withErrors(['sdt' => 'Số điện thoại này đã đăng ký. Đăng nhập, hoặc nhắn Zalo cho xưởng nếu quên mật khẩu.'])->withInput();
        }
        PodSeller::create([
            'ma' => PodSeller::taoMa(), 'ten' => $v['ten'], 'sdt' => $sdt, 'matkhau' => Hash::make($v['matkhau']),
            'hien' => false, 'cho_duyet' => true, 'dong_y_luc' => now(),
            'san_ban' => $v['san_ban'] ?? null, 'link_shop' => $v['link_shop'] ?? null, 'loi_nhan' => $v['loi_nhan'] ?? null,
        ]);
        return redirect()->route('pod.site.dang-ky')->with('xong', true);
    }

    public function robots()
    {
        $u = rtrim(config('pod.url'), '/');
        $txt = "User-agent: *\nAllow: /\nDisallow: /cong\nDisallow: /tho-in\nDisallow: /api/\n\nSitemap: $u/sitemap.xml\n";
        return response($txt, 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }

    public function sitemap()
    {
        $u = rtrim(config('pod.url'), '/');
        $dong = [[$u . '/', null], [$u . '/kho-mau', null], [$u . '/bang-gia', null], [$u . '/dang-ky', null]];
        foreach ($this->khoChung()->orderByDesc('id')->get(['ma', 'updated_at']) as $m) {
            $dong[] = [$u . '/kho-mau/' . $m->ma, optional($m->updated_at)->toDateString()];
        }
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        foreach ($dong as [$loc, $lm]) {
            $xml .= '  <url><loc>' . htmlspecialchars($loc, ENT_XML1) . '</loc>' . ($lm ? "<lastmod>$lm</lastmod>" : '') . "</url>\n";
        }
        return response($xml . "</urlset>\n", 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }
}
