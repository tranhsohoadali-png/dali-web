<?php

namespace App\Support;

use Illuminate\Http\Request;

/**
 * Mã CTV của tomau.tranhdali.vn mang sang qua ?tref=<mã>. Tách BIỆT hoàn toàn
 * với affiliate_code (CTV nội bộ tranhdali): tomau_ref chỉ để BÁO đơn về cổng
 * tomau, KHÔNG cộng vào sổ CTV của tranhdali.
 */
class TomauRef
{
    // Khớp REF_CODE_RE bên tomau: chữ/số/gạch, 2–32 ký tự.
    private const PATTERN = '/^[a-zA-Z0-9_-]{2,32}$/';
    public const KEY = 'tomau_ref';
    private const TTL_MIN = 60 * 24 * 30; // 30 ngày (phút)

    public static function clean(?string $raw): ?string
    {
        $v = trim((string) $raw);
        return $v !== '' && preg_match(self::PATTERN, $v) ? $v : null;
    }

    /** Bắt ?tref=<mã> trên URL -> lưu session + cookie 30 ngày. */
    public static function capture(Request $request): void
    {
        $code = self::clean($request->query('tref'));
        if ($code !== null) {
            session([self::KEY => $code]);
            cookie()->queue(self::KEY, $code, self::TTL_MIN);
        }
    }

    /**
     * Mã hiện hành để gắn vào đơn: session > cookie. KHÔNG nhận từ input của
     * form đặt hàng (giảm rủi ro khách tự gán mã tuỳ ý). Null nếu không có/không hợp lệ.
     */
    public static function current(Request $request): ?string
    {
        return self::clean($request->session()->get(self::KEY))
            ?? self::clean($request->cookie(self::KEY));
    }
}
