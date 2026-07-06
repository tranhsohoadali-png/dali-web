<?php

namespace App\Http\Middleware;

use App\Support\TomauRef;
use Closure;
use Illuminate\Http\Request;

/**
 * Bắt ?tref=<mã CTV tomau> trên mọi trang GET công khai (khách bấm banner từ
 * tomau sang tranhdali.vn/thiet-ke?tref=...). Lưu session + cookie 30 ngày để
 * gắn vào đơn khi khách đặt. Bỏ qua /admin.
 */
class CaptureTomauRef
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->isMethod('GET') && !$request->is('admin*')) {
            TomauRef::capture($request);
        }
        return $next($request);
    }
}
