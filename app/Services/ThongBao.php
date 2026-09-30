<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

/** Gửi tin báo xưởng qua Telegram (tg_token / tg_chat_id ở Admin › Cài đặt). Lỗi thì bỏ qua, không chặn luồng chính. */
class ThongBao
{
    public static function telegram(string $msg): void
    {
        try {
            $s = DB::table('admin_settings')->whereIn('key', ['tg_token', 'tg_chat_id'])->pluck('value', 'key');
            $tok = trim((string) ($s['tg_token'] ?? ''));
            $chat = trim((string) ($s['tg_chat_id'] ?? ''));
            if ($tok === '' || $chat === '') return;
            Http::timeout(6)->post("https://api.telegram.org/bot{$tok}/sendMessage", ['chat_id' => $chat, 'text' => $msg]);
        } catch (\Throwable $e) {
            // im lặng: thông báo là phụ
        }
    }
}
