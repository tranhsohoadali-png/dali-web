<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

/**
 * Đọc ảnh danh sách môn / thời khoá biểu bằng Claude (vision) → [{mon, so_luong}].
 * Dùng chung cho: đại lý (storefront) và trang quản trị Đơn đi hộ.
 */
class DocMonAi
{
    /** Khoá API: admin_settings (trang Cài đặt) trước, .env sau. */
    public static function key(): ?string
    {
        $k = DB::table('admin_settings')->where('key', 'anthropic_key')->value('value');
        if ($k !== null && trim((string) $k) !== '') return trim((string) $k);
        $env = config('services.anthropic.key');
        return $env ? (string) $env : null;
    }

    public static function model(): string
    {
        $m = DB::table('admin_settings')->where('key', 'anthropic_model')->value('value');
        if ($m !== null && trim((string) $m) !== '') return trim((string) $m);
        return (string) config('services.anthropic.model', 'claude-opus-5');
    }

    public static function batAi(): bool { return (bool) self::key(); }

    /**
     * @return array ['ok'=>bool, 'error'?=>string, 'mon'=>[{mon,so_luong}], 'tong'=>int, 'text'=>string]
     */
    public static function doc(string $bytes, string $mime): array
    {
        $key = self::key();
        if (!$key) return ['ok' => false, 'error' => 'Tính năng AI chưa được bật (thiếu khoá API).'];
        if (!in_array($mime, ['image/jpeg', 'image/png', 'image/webp'], true)) {
            return ['ok' => false, 'error' => 'Chỉ đọc được ảnh JPG/PNG/WEBP.'];
        }
        try {
            $sys = 'Bạn là trợ lý nhập liệu của xưởng in DALI 3D. Người dùng gửi ảnh chụp DANH SÁCH MÔN HỌC hoặc THỜI KHOÁ BIỂU và số lượng thẻ cần đặt (viết tay hoặc in). '
                 . 'Đọc chính xác, CHỈ dùng thông tin thấy trong ảnh, KHÔNG bịa. Chuẩn hoá tên môn về tiếng Việt có dấu. '
                 . 'Nếu là THỜI KHOÁ BIỂU (lưới môn theo tiết/thứ): ĐẾM số lần mỗi môn xuất hiện làm so_luong (bỏ các ô "Chào cờ", "Ra chơi", "Sinh hoạt", trống). '
                 . 'Nếu là danh sách môn kèm số: lấy đúng số đó. Nếu một dòng không rõ số lượng, đặt so_luong = 1.';
            $ask = 'Trả về DUY NHẤT JSON dạng {"mon":[{"mon":"Toán","so_luong":3},{"mon":"Tiếng Việt","so_luong":2}]}. '
                 . 'Không thêm chữ nào ngoài JSON. Nếu ảnh không phải danh sách môn/thời khoá biểu, trả {"mon":[]}.';
            $content = [
                ['type' => 'image', 'source' => ['type' => 'base64', 'media_type' => $mime, 'data' => base64_encode($bytes)]],
                ['type' => 'text', 'text' => $ask],
            ];
            $resp = Http::withHeaders(['x-api-key' => $key, 'anthropic-version' => '2023-06-01'])
                ->timeout(60)->post('https://api.anthropic.com/v1/messages', [
                    'model' => self::model(), 'max_tokens' => 1500, 'system' => $sys,
                    'messages' => [['role' => 'user', 'content' => $content]],
                ]);
            if (!$resp->successful()) return ['ok' => false, 'error' => 'AI lỗi (HTTP ' . $resp->status() . ').'];
            $textOut = collect($resp->json('content') ?: [])->where('type', 'text')->pluck('text')->implode("\n");
            $json = self::jsonTuText($textOut);

            $mon = [];
            foreach ((array) ($json['mon'] ?? []) as $r) {
                if (!is_array($r)) continue;
                $ten = mb_substr(trim((string) ($r['mon'] ?? '')), 0, 40);
                if ($ten === '') continue;
                $sl = max(1, min(999, (int) ($r['so_luong'] ?? 1)));
                $mon[] = ['mon' => $ten, 'so_luong' => $sl];
                if (count($mon) >= 80) break;
            }
            $text = collect($mon)->map(fn ($m) => $m['mon'] . ' x' . $m['so_luong'])->implode(', ');
            $tong = (int) array_sum(array_column($mon, 'so_luong'));
            return ['ok' => true, 'mon' => $mon, 'tong' => $tong, 'text' => $text];
        } catch (\Throwable $e) {
            return ['ok' => false, 'error' => 'AI chưa đọc được ảnh.'];
        }
    }

    private static function jsonTuText(string $text): array
    {
        $t = preg_replace('/```(?:json)?/i', '', trim($text));
        $s = strpos($t, '{'); $e = strrpos($t, '}');
        if ($s === false || $e === false || $e < $s) return [];
        $d = json_decode(substr($t, $s, $e - $s + 1), true);
        return is_array($d) ? $d : [];
    }
}
