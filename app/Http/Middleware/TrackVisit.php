<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrackVisit
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        try {
            // Chỉ ghi: GET, không phải admin, không phải ajax/json, không phải file tĩnh
            $path = $request->path();
            $skip = $request->method() !== 'GET'
                || $request->ajax()
                || $request->wantsJson()
                || str_starts_with($path, 'admin')
                || preg_match('#\.(css|js|png|jpe?g|gif|svg|ico|webp|woff2?|map|xml|txt)$#i', $path)
                || $path === 'gio-hang/so-luong'; // bỏ qua polling giỏ hàng

            if (!$skip) {
                DB::table('visits')->insert([
                    'ip'         => $request->ip(),
                    'path'       => '/' . ltrim($path, '/'),
                    'referer'    => $request->headers->get('referer'),
                    'date'       => now()->toDateString(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        } catch (\Throwable $e) {
            // Không để lỗi tracking ảnh hưởng tới website
        }

        return $response;
    }
}
