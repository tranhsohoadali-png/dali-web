<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Bảo vệ các route /api/integration/* bằng token bí mật chung (X-Integration-Token
 * hoặc Authorization: Bearer ...). So khớp an toàn bằng hash_equals.
 */
class IntegrationAuth
{
    public function handle(Request $request, Closure $next)
    {
        // IP allowlist tùy chọn (để trống = cho mọi IP, chỉ dựa vào token).
        $allow = (array) config('integration.ip_allowlist', []);
        if (!empty($allow) && !in_array($request->ip(), $allow, true)) {
            return response()->json(['ok' => false, 'msg' => 'Forbidden'], 403);
        }

        $expected = (string) config('integration.token');

        $got = (string) ($request->header('X-Integration-Token')
            ?: preg_replace('/^Bearer\s+/i', '', (string) $request->header('Authorization')));

        if ($expected === '' || $got === '' || !hash_equals($expected, $got)) {
            return response()->json(['ok' => false, 'msg' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
