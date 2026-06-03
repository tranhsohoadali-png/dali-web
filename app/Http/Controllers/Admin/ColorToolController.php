<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

/**
 * Proxy Django color tool (port 18001) qua HTTPS admin.
 * Giải quyết mixed-content khi admin dùng HTTPS nhưng tool chạy HTTP local.
 */
class ColorToolController extends Controller
{
    private function toolBase(): string
    {
        return rtrim(
            DB::table('admin_settings')->where('key', 'color_tool_url')->value('value')
            ?: 'http://127.0.0.1:18001',
            '/'
        );
    }

    public function index()
    {
        $toolUrl  = $this->toolBase();
        $proxyUrl = route('admin.colortool.proxy', ['path' => '']);
        return view('admin.colortool', compact('toolUrl', 'proxyUrl'));
    }

    /** Proxy tất cả request GET/POST (bao gồm upload file) sang Django tool. */
    public function proxy(Request $request, string $path = '')
    {
        $base = $this->toolBase();
        $url  = $base . '/' . ltrim($path, '/');
        if ($request->getQueryString()) {
            $url .= '?' . $request->getQueryString();
        }

        try {
            $method = strtolower($request->method());

            /* ---- Gọi Django ---- */
            if ($method === 'get') {
                $resp = Http::timeout(20)->get($url);
            } elseif ($request->hasFile('image')) {
                // Upload file ảnh
                $file  = $request->file('image');
                $resp  = Http::timeout(60)
                    ->attach('image', file_get_contents($file->getRealPath()), $file->getClientOriginalName())
                    ->post($url, $request->except('image'));
            } else {
                // POST JSON / form thường
                $resp = Http::timeout(30)
                    ->asForm()
                    ->post($url, $request->all());
            }

            $contentType = $resp->header('Content-Type', 'text/html; charset=utf-8');
            $body        = $resp->body();
            $status      = $resp->status();

            /* ---- Với HTML: thêm <base> để relative URL tự resolve ---- */
            if (str_contains($contentType, 'text/html')) {
                $proxyBase = url('/admin/cong-cu-tach-mau/proxy/');
                // Thêm <base> ngay sau <head>
                $body = preg_replace(
                    '/<head([^>]*)>/i',
                    '<head$1><base href="' . $proxyBase . '">',
                    $body, 1
                );
                // Sửa /media/ → proxy/media/  (ảnh kết quả)
                $body = str_replace('src="/media/', 'src="' . $proxyBase . 'media/', $body);
                $body = str_replace("src='/media/", "src='" . $proxyBase . 'media/', $body);
                // Sửa JS: '/media/' literal trong code JS
                $body = str_replace("'/media/", "'" . $proxyBase . 'media/', $body);
                $body = str_replace('"/media/', '"' . $proxyBase . 'media/', $body);
            }

            /* ---- Truyền headers quan trọng (download) ---- */
            $headers = ['Content-Type' => $contentType];
            if ($cd = $resp->header('Content-Disposition')) {
                $headers['Content-Disposition'] = $cd;
            }

            return response($body, $status)->withHeaders($headers);

        } catch (\Throwable $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['status' => 'error', 'error' => 'Tool chưa chạy. Mở run.bat (cổng 18001).'], 503);
            }
            return response($this->offlinePage(), 503)
                ->header('Content-Type', 'text/html; charset=utf-8');
        }
    }

    private function offlinePage(): string
    {
        $settingsUrl = route('admin.settings');
        return <<<HTML
        <!DOCTYPE html><html lang="vi"><head><meta charset="UTF-8">
        <style>
        body{font-family:'Be Vietnam Pro',sans-serif;background:#F2FDE8;color:#1A4D00;
          display:flex;flex-direction:column;align-items:center;justify-content:center;
          min-height:100vh;text-align:center;padding:30px;margin:0}
        h2{font-size:22px;margin-bottom:12px}
        p{font-size:14px;color:#4A8A1A;line-height:1.7;max-width:460px;margin:0 auto 20px}
        code{background:#E8F9D0;padding:2px 8px;border-radius:5px;font-size:13px}
        a{color:#6BBF1F;font-weight:700}
        </style></head><body>
        <div style="font-size:52px;margin-bottom:16px">🎨</div>
        <h2>Công cụ tách màu chưa kết nối</h2>
        <p>Để dùng công cụ, hãy chạy <code>run.bat</code> trong thư mục
        <code>index_color_dali_merged</code> trên máy này.<br>
        Công cụ sẽ lắng nghe tại <code>http://127.0.0.1:18001</code>.</p>
        <p>Nếu chạy ở địa chỉ/cổng khác, cập nhật trong
        <a href="$settingsUrl">Cài đặt → Công cụ tách màu</a>.</p>
        </body></html>
        HTML;
    }
}
