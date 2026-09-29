<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

/**
 * Gọi app "thay tên mẫu" (FastAPI, nội bộ 127.0.0.1:8021) để sinh file 3MF in tên.
 * Nối thẳng từ đơn Đi-đơn-hộ: tên lấy y nguyên từ DB, xưởng KHÔNG gõ lại → hết lỗi chép tay.
 * Bảo vệ: gửi header X-ThayTen-Khoa == THAYTEN_KHOA (giống nginx gắn khi vào qua admin).
 */
class ThayTenClient
{
    public static function batDuoc(): bool
    {
        return trim((string) config('services.thayten.khoa')) !== '';
    }

    private static function url(): string
    {
        return rtrim((string) config('services.thayten.url', 'http://127.0.0.1:8021'), '/');
    }

    private static function http(int $giay = 20)
    {
        return Http::withHeaders(['X-ThayTen-Khoa' => (string) config('services.thayten.khoa')])
            ->acceptJson()->timeout($giay);
    }

    /**
     * Sinh file 3MF in tên cho một mẫu, chờ dựng xong rồi tải nội dung về.
     * @return array ['ok'=>true,'ten_file'=>string,'canh_bao'=>string[],'noidung'=>string(bytes)]
     *               | ['ok'=>false,'error'=>string]
     */
    public static function taoFile(string $mid, string $ten): array
    {
        if (!self::batDuoc()) return ['ok' => false, 'error' => 'Chưa cấu hình app thay tên (thiếu THAYTEN_KHOA).'];
        $ten = trim($ten);
        if ($ten === '') return ['ok' => false, 'error' => 'Dòng này chưa có tên in.'];
        $url = self::url();
        try {
            $r = self::http()->post($url . '/api/dung', ['mau' => $mid, 'ten' => $ten]);
            if (!$r->successful()) {
                $msg = $r->json('cau') ?? $r->json('error') ?? ('HTTP ' . $r->status());
                return ['ok' => false, 'error' => 'App thay tên từ chối: ' . $msg];
            }
            $vid = trim((string) $r->json('id'));
            if ($vid === '') return ['ok' => false, 'error' => 'App thay tên không trả mã việc.'];

            $kq = null;
            for ($i = 0; $i < 25; $i++) {
                usleep($i === 0 ? 1_500_000 : 2_500_000); // 1,5s rồi 2,5s mỗi vòng (tối đa ~61s)
                $s = self::http(12)->get($url . '/api/viec/' . $vid);
                if (!$s->successful()) continue;
                $tt = (string) $s->json('trang_thai');
                if ($tt === 'xong') { $kq = $s->json(); break; }
                if ($tt === 'loi')  return ['ok' => false, 'error' => 'Dựng lỗi: ' . ($s->json('loi_nhan') ?? 'không rõ')];
            }
            if (!$kq) return ['ok' => false, 'error' => 'App thay tên dựng quá lâu, thử lại.'];

            $t = self::http(40)->get($url . '/api/viec/' . $vid . '/tai');
            if (!$t->successful()) return ['ok' => false, 'error' => 'Không tải được file 3MF (HTTP ' . $t->status() . ').'];

            return [
                'ok'       => true,
                'ten_file' => (string) (data_get($kq, 'ket_qua.ten_file') ?: 'in-ten.3mf'),
                'canh_bao' => array_values((array) (data_get($kq, 'ket_qua.canh_bao') ?: [])),
                'noidung'  => $t->body(),
            ];
        } catch (\Throwable $e) {
            return ['ok' => false, 'error' => 'Không gọi được app thay tên.'];
        }
    }
}
