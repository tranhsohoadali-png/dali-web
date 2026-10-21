<?php
namespace App\Services;

use Illuminate\Support\Facades\Cache;

/**
 * Danh sách TÊN THẺ MÔN đang có trong Thư viện file in (mau.tranhdali.vn, cùng VPS):
 * đọc file.json của thư viện -> nhóm "Thẻ môn 0.2 / 0.4 / Thẻ ít dùng", bỏ thẻ giờ (7:10-7:30…).
 * Xưởng thêm thẻ mới vào thư viện là bộ đọc môn tự nhận, không phải sửa code.
 */
class ThuVienThe
{
    /** Bản dự phòng khi không đọc được file thư viện (chép 2026-10-21). */
    private const DU_PHONG = [
        'TIẾNG VIỆT', 'TOÁN', 'ENGLISH', 'HĐTN', 'GDTC', 'GDĐP', 'ÂM NHẠC', 'TN & XH', 'ĐẠO ĐỨC', 'MĨ THUẬT',
        'KN SỐNG', 'LS & ĐL', 'TIN HỌC', 'KHOA HỌC', 'CÔNG NGHỆ', 'CHÀO CỜ', 'SH LỚP', 'STEM', 'BTKT', 'TALK',
        'GDCD', 'NGỮ VĂN', 'LỊCH SỬ', 'ĐỊA LÝ', 'VẬT LÍ', 'HÓA HỌC', 'SINH HỌC', 'KHTN', 'TỰ HỌC', 'TRUY BÀI',
        'RÈN LUYỆN', 'CLB', 'TOÁN CLB', 'VĂN CLB', 'THQT', 'ÔN TOÁN', 'ÔN T.VIỆT', 'ÔN TẬP', 'L.TOÁN', 'L.T.VIỆT',
        'R.TOÁN', 'R.T.VIỆT', 'TOÁN HÌNH', 'TOÁN ĐẠI', 'TOÁN TA', 'KHTA', 'TABN', 'TANN 2', 'NN5TA', 'TA D&C',
        'NN-CLB', 'KNS3NK', 'KĨ NĂNG', 'AEROBIC', 'CỜ', 'HĐCC', 'SHDC', 'CDS', 'TTNT', 'AI', 'ĐỌC SÁCH', 'TRÔNG GIỮ',
    ];

    public static function danhSach(): array
    {
        return Cache::remember('thu-vien-the:ten', 600, function () {
            $f = (string) config('services.thu_vien_the.file', '/var/www/dali_pha_web/media/print/file.json');
            $ds = [];
            try {
                $j = is_readable($f) ? json_decode((string) file_get_contents($f), true) : null;
                foreach ((array) $j as $it) {
                    if (!is_array($it)) continue;
                    $nhom = (string) ($it['nhom'] ?? '');
                    if (!str_starts_with($nhom, 'Thẻ môn') && $nhom !== 'Thẻ ít dùng') continue;
                    $ten = trim((string) ($it['ten'] ?? ''));
                    if (class_exists('Normalizer')) $ten = (string) \Normalizer::normalize($ten, \Normalizer::FORM_C); // tên file có thể là NFD -> trùng tên
                    $ten = preg_replace('/\s+/u', ' ', $ten);
                    $ten = trim(preg_replace('/_\d+the$/u', '', $ten));          // CHÀO_CỜ_42the -> CHÀO_CỜ
                    $ten = trim(str_replace('_', ' ', $ten));
                    $ten = trim(preg_replace('/\s*\(.*\)$/u', '', $ten));          // "KHOA HỌC (trần đặc - thử)" -> KHOA HỌC
                    if ($ten === '' || preg_match('/\d{1,2}:\d{2}/', $ten)) continue; // thẻ giờ
                    if (!in_array($ten, $ds, true)) $ds[] = $ten;
                }
            } catch (\Throwable $e) {
                $ds = [];
            }
            if (count($ds) < 20) return self::DU_PHONG;                            // file lạ/thiếu -> dùng bản chép
            foreach (self::DU_PHONG as $t) if (!in_array($t, $ds, true)) $ds[] = $t; // giữ thẻ đã biết dù bị gỡ khỏi thư viện
            return $ds;
        });
    }
}
