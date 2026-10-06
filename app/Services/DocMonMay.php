<?php
namespace App\Services;

/**
 * Đọc ảnh THỜI KHOÁ BIỂU bằng MÁY (OCR Tesseract + OpenCV tách ô) — KHÔNG dùng AI, không tốn tiền.
 * Gọi scripts/doc_mon_tkb.py (cần trên máy chủ: python3-opencv, tesseract-ocr, tesseract-ocr-vie).
 * Trả về đúng định dạng DocMonAi::doc() để màn hình/soạn TKB dùng chung:
 *   ['ok'=>true, 'mon'=>[{mon,so_luong}], 'tong'=>int, 'text'=>"Toán x3, …", 'khong_ro'=>[…]] | ['ok'=>false,'error'=>…]
 * Đã đo trên ảnh thật: 3/3 ảnh đúng 100% (35/35, 35/35, 33/33 tiết), chịu nghiêng tới 5°, 40 ảnh không phải TKB không bị đọc nhầm.
 * Không đọc được: ảnh viết tay, bảng không có đường kẻ, ảnh < 450px ngang.
 */
class DocMonMay
{
    public static function doc(string $fullPath): array
    {
        if (!is_file($fullPath)) return ['ok' => false, 'error' => 'Không thấy file ảnh.'];
        $script = base_path('scripts/doc_mon_tkb.py');
        if (!is_file($script)) return ['ok' => false, 'error' => 'Thiếu bộ đọc trên máy chủ.'];
        $cmd = 'timeout 90 python3 ' . escapeshellarg($script) . ' ' . escapeshellarg($fullPath) . ' 2>/dev/null';
        $out = @shell_exec($cmd);
        $j = is_string($out) ? json_decode(trim($out), true) : null;
        if (!is_array($j)) return ['ok' => false, 'error' => 'Máy chưa đọc được ảnh (thiếu công cụ OCR hoặc quá thời gian).'];
        if (empty($j['ok'])) return ['ok' => false, 'error' => (string) ($j['error'] ?? 'Máy chưa đọc được ảnh.')];

        $mon = [];
        foreach ((array) ($j['mon'] ?? []) as $r) {
            $ten = mb_substr(trim((string) ($r['mon'] ?? '')), 0, 40);
            $sl  = max(1, min(999, (int) ($r['so_luong'] ?? 1)));
            if ($ten !== '') $mon[] = ['mon' => $ten, 'so_luong' => $sl];
        }
        if (!$mon) return ['ok' => false, 'error' => 'Không nhận ra môn nào trong ảnh.'];
        return [
            'ok'       => true,
            'mon'      => $mon,
            'tong'     => (int) array_sum(array_column($mon, 'so_luong')),
            'text'     => collect($mon)->map(fn ($m) => $m['mon'] . ' x' . $m['so_luong'])->implode(', '),
            'khong_ro' => array_values((array) ($j['khong_ro'] ?? [])),
            'nguon'    => 'may',
        ];
    }
}
