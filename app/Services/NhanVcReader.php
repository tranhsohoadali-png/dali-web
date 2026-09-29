<?php
namespace App\Services;

/**
 * Đọc MÃ VẬN ĐƠN từ nhãn KHÔNG cần AI: rút lớp chữ trong PDF (pdftotext) rồi dò mẫu.
 * Nhãn Shopee/SPX, GHTK, VNPost... xuất PDF đều có lớp chữ nên bắt được miễn phí.
 * Nhãn là ẢNH (jpg/png) hoặc PDF scan (không có chữ) thì trả ok=false → để AI lo.
 */
class NhanVcReader
{
    /** @return array ['ok'=>true,'ma_vc'=>string,'vc'=>string,'nguon'=>'pdf-text'] | ['ok'=>false] */
    public static function doc(string $fullPath, string $mime): array
    {
        if (stripos($mime, 'pdf') === false) return ['ok' => false];
        $text = self::rutChuPdf($fullPath);
        if (trim($text) === '') return ['ok' => false];
        return self::doTuText($text);
    }

    public static function coCongCu(): bool
    {
        return trim((string) @shell_exec('command -v pdftotext 2>/dev/null')) !== '';
    }

    private static function rutChuPdf(string $path): string
    {
        if (!self::coCongCu() || !is_file($path)) return '';
        $out = @shell_exec('pdftotext -layout -q ' . escapeshellarg($path) . ' - 2>/dev/null');
        return is_string($out) ? $out : '';
    }

    /** Dò mã vận đơn trong đoạn văn bản đã rút. */
    public static function doTuText(string $text): array
    {
        $t = preg_replace('/[ \t]+/', ' ', $text);

        // 1) SPX / Shopee Express — phổ biến nhất, mẫu rất đặc trưng
        if (preg_match('/\bSPXVN[0-9A-Z]{6,}\b/i', $t, $m)) {
            $ma = strtoupper($m[0]);
            return ['ok' => true, 'ma_vc' => $ma, 'vc' => 'SPX', 'nguon' => 'pdf-text'];
        }
        // 2) VNPost / EMS: 2 chữ + 9 số + VN (VD: EG123456789VN)
        if (preg_match('/\b[A-Z]{2}[0-9]{9}VN\b/i', $t, $m)) {
            $ma = strtoupper($m[0]);
            return ['ok' => true, 'ma_vc' => $ma, 'vc' => 'VNPost', 'nguon' => 'pdf-text'];
        }
        // 3) Bám nhãn "vận đơn" rồi lấy mã ngay sau (các đơn vị khác)
        if (preg_match('/v[ậaă]n\s*đ[ơo]n[^A-Za-z0-9]{0,6}([A-Z0-9][A-Z0-9\-]{7,29})/iu', $t, $m)) {
            $ma = strtoupper(trim($m[1], '-'));
            return ['ok' => true, 'ma_vc' => $ma, 'vc' => self::vcTuMa($ma), 'nguon' => 'pdf-text'];
        }
        return ['ok' => false];
    }

    private static function vcTuMa(string $ma): string
    {
        if (str_starts_with($ma, 'SPX')) return 'SPX';
        if (preg_match('/^[A-Z]{2}[0-9]{9}VN$/', $ma)) return 'VNPost';
        if (str_starts_with($ma, 'VTP')) return 'Viettel Post';
        if (str_starts_with($ma, 'GHN')) return 'GHN';
        return '';
    }
}
