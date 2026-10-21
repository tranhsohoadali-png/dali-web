<?php
namespace App\Services;

use App\Models\DocMonLuu;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

/**
 * Đọc ảnh THỜI KHOÁ BIỂU / danh sách môn -> số thẻ cần in, theo ĐÚNG TÊN THẺ trong Thư viện file in.
 * Chủ xưởng chốt 2026-10-21: AI (Claude Sonnet 5.5) đọc CHÍNH; AI lỗi / hết credit -> bộ đọc MÁY (OCR) dự phòng.
 * Đo trên 9 ảnh thật (324 ô tiết): câu lệnh này cho AI đúng 646/648 ô (2 lượt); bộ đọc máy sau khi sửa 323/324.
 * Thời gian: nginx cắt request PHP sau 60 s -> AI tối đa 45 s, máy (~5 s/ảnh) còn kịp chạy dự phòng.
 *
 * Trả về: ['ok', 'mon'=>[{mon,so_luong}], 'tong', 'text', 'khong_ro'=>[…], 'nguon'=>'ai'|'may', 'canh_bao'?, 'error'?]
 */
class DocMonTkb
{
    private const MODEL_MAC_DINH = 'claude-sonnet-5-5';

    /**
     * Kết quả LƯU theo sha256 ảnh (bảng doc_mon_luu, chủ xưởng yêu cầu 2026-10-21 để đỡ tốn phí AI):
     * ảnh AI đã đọc -> trả lại ngay, không gọi AI; kết quả máy chỉ là tạm (lần sau vẫn thử AI trước).
     * @param bool $docLai true = bỏ qua kết quả đã lưu, đọc lại (tốn phí AI) — nút "Đọc lại" ở admin.
     */
    public static function doc(string $fullPath, string $mime, bool $docLai = false): array
    {
        $hash = is_file($fullPath) ? (string) hash_file('sha256', $fullPath) : '';
        $luu = self::daLuu($hash);
        $coAi = $luu && $luu['nguon'] === 'ai';
        if (!$docLai && $coAi) return $luu;

        $ai = self::docAi($fullPath, $mime);
        if (!empty($ai['ok']) || ($ai['khong_phai_tkb'] ?? false) === true) {   // AI khẳng định không phải TKB: đừng để máy đoán bừa
            self::luu($hash, $ai);
            return $ai;
        }
        $lyDo = (string) ($ai['error'] ?? 'AI không dùng được');
        if ($coAi) {   // ĐỌC LẠI mà AI lỗi: giữ kết quả AI đã lưu, đừng đưa kết quả máy (hay sót ô) ra thay trên màn hình
            return array_merge($luu, ['canh_bao' => 'Đọc lại bằng AI không được (' . $lyDo . ') — vẫn giữ kết quả AI đã lưu.']);
        }
        $may = DocMonMay::doc($fullPath);
        if (!empty($may['ok'])) {
            self::luu($hash, $may);
            $may['canh_bao'] = 'Máy đọc thay AI (' . $lyDo . ') — máy hay sót ô, KIỂM LẠI kỹ với ảnh.';
            return $may;
        }
        return ['ok' => false, 'error' => $lyDo . ' · Máy cũng chưa đọc được: ' . ($may['error'] ?? '') ];
    }

    /** Kết quả đã lưu của ảnh (theo sha256) hoặc null; kèm tu_luu=true + luu_luc để màn hình ghi "đã lưu, không tốn phí". */
    public static function daLuu(string $hash): ?array
    {
        if ($hash === '') return null;
        $r = DocMonLuu::where('sha256', $hash)->first();
        if (!$r || !is_array($r->ket_qua)) return null;
        return array_merge($r->ket_qua, ['nguon' => $r->nguon, 'tu_luu' => true, 'luu_luc' => optional($r->updated_at)->format('d/m/Y H:i')]);
    }

    public static function daLuuTheoFile(string $fullPath): ?array
    {
        return is_file($fullPath) ? self::daLuu((string) hash_file('sha256', $fullPath)) : null;
    }

    private static function luu(string $hash, array $kq): void
    {
        if ($hash === '') return;
        $nguon = (($kq['nguon'] ?? '') === 'ai') ? 'ai' : 'may';
        try {
            $cu = DocMonLuu::where('sha256', $hash)->first();
            if ($cu && $cu->nguon === 'ai' && $nguon === 'may') return;          // kết quả máy không đè kết quả AI
            unset($kq['canh_bao'], $kq['tu_luu'], $kq['luu_luc']);
            DocMonLuu::updateOrCreate(['sha256' => $hash], ['nguon' => $nguon, 'model' => $nguon === 'ai' ? self::model() : null, 'ket_qua' => $kq]);
        } catch (\Throwable $e) {
            // lưu hỏng (VD hai lần đọc cùng lúc đụng khoá unique) không được làm hỏng kết quả đọc
        }
    }

    public static function model(): string
    {
        $m = DB::table('admin_settings')->where('key', 'anthropic_model_tkb')->value('value');
        return ($m !== null && trim((string) $m) !== '') ? trim((string) $m) : self::MODEL_MAC_DINH;
    }

    public static function docAi(string $fullPath, string $mime): array
    {
        $key = DocMonAi::key();
        if (!$key) return ['ok' => false, 'error' => 'chưa bật AI (thiếu khoá API)'];
        if (!in_array($mime, ['image/jpeg', 'image/png', 'image/webp'], true)) return ['ok' => false, 'error' => 'AI chỉ đọc ảnh JPG/PNG/WEBP'];
        if (!is_file($fullPath)) return ['ok' => false, 'error' => 'không thấy file ảnh'];

        $the = ThuVienThe::danhSach();
        $sys = self::prompt($the);
        $schema = self::schema($the);
        try {
            $resp = Http::withHeaders(['x-api-key' => $key, 'anthropic-version' => '2023-06-01'])
                ->timeout(45)->post('https://api.anthropic.com/v1/messages', [
                    'model'         => self::model(),
                    'max_tokens'    => 8000,
                    'system'        => $sys,
                    // việc chủ yếu là NHÌN ô và chép chữ -> effort thấp cho nhanh; khuôn JSON bắt buộc tên thẻ đúng thư viện
                    'output_config' => ['effort' => 'low', 'format' => ['type' => 'json_schema', 'schema' => $schema]],
                    'messages'      => [['role' => 'user', 'content' => [
                        ['type' => 'image', 'source' => ['type' => 'base64', 'media_type' => $mime, 'data' => base64_encode((string) file_get_contents($fullPath))]],
                        ['type' => 'text', 'text' => 'Đọc ảnh này và liệt kê mọi ô tiết học theo đúng quy tắc.'],
                    ]]],
                ]);
        } catch (\Throwable $e) {
            return ['ok' => false, 'error' => 'không gọi được AI (mạng/quá thời gian)'];
        }
        if (!$resp->successful()) return ['ok' => false, 'error' => DocMonAi::loiHttpPublic($resp)];
        $stop = (string) $resp->json('stop_reason');
        if ($stop === 'refusal')    return ['ok' => false, 'error' => 'AI từ chối đọc ảnh này'];
        if ($stop === 'max_tokens') return ['ok' => false, 'error' => 'AI trả lời bị cắt (ảnh quá nhiều ô)'];
        $text = collect($resp->json('content') ?: [])->where('type', 'text')->pluck('text')->implode('');
        $j = json_decode($text, true);
        if (!is_array($j)) return ['ok' => false, 'error' => 'AI trả về sai định dạng'];
        $usage = ['in' => (int) $resp->json('usage.input_tokens'), 'out' => (int) $resp->json('usage.output_tokens')];
        if (($j['la_tkb'] ?? true) === false) {
            return ['ok' => false, 'khong_phai_tkb' => true, 'nguon' => 'ai', 'error' => 'AI: ảnh này không phải thời khoá biểu/danh sách môn.', 'usage' => $usage];
        }

        $dem = []; $ten = []; $khongRo = [];
        foreach ((array) ($j['o'] ?? []) as $o) {
            if (!is_array($o)) continue;
            $t = (string) ($o['the'] ?? '');
            if ($t === 'KHONG_PHAI_TIET') continue;
            $sl = max(1, min(999, (int) ($o['so_luong'] ?? 1)));
            if ($t === 'KHAC' || !in_array($t, $the, true)) {
                $chu = mb_substr(trim(preg_replace('/\s+/u', ' ', (string) ($o['chu'] ?? ''))), 0, 40);
                if ($chu === '') continue;
                $k = 'khac:' . mb_strtolower($chu);
                $ten[$k] = $ten[$k] ?? $chu;
                if (!in_array($ten[$k], $khongRo, true)) $khongRo[] = $ten[$k];
            } else {
                $k = $t; $ten[$k] = $t;
            }
            $dem[$k] = ($dem[$k] ?? 0) + $sl;
        }
        if (!$dem) return ['ok' => false, 'error' => 'AI không thấy ô tiết học nào trong ảnh.', 'usage' => $usage];
        arsort($dem);
        $mon = [];
        foreach ($dem as $k => $n) $mon[] = ['mon' => $ten[$k], 'so_luong' => $n];
        return [
            'ok'       => true,
            'mon'      => $mon,
            'tong'     => (int) array_sum($dem),
            'text'     => collect($mon)->map(fn ($m) => $m['mon'] . ' x' . $m['so_luong'])->implode(', '),
            'khong_ro' => $khongRo,
            'nguon'    => 'ai',
            'usage'    => $usage,
        ];
    }

    /** Câu lệnh hệ thống cho AI (public để kiểm định offline cùng một câu lệnh). */
    public static function prompt(array $the): string
    {
        return "Bạn là trợ lý soạn thẻ môn của xưởng in DALI 3D. Xưởng làm BẢNG THỜI KHOÁ BIỂU lắp thẻ: mỗi tiết học trên bảng là một THẺ in tên môn.\n"
             . "Người dùng gửi ảnh THỜI KHOÁ BIỂU của một lớp (bảng in, ảnh chụp màn hình Excel/Word, ảnh chụp giấy, có khi viết tay) hoặc một DANH SÁCH môn kèm số lượng.\n"
             . "Việc của bạn: liệt kê MỌI Ô TIẾT HỌC trong lưới (mỗi ô = 1 mục, so_luong = 1) và gán mỗi ô vào đúng MỘT thẻ trong danh sách thẻ của xưởng.\n\n"
             . "DANH SÁCH THẺ CỦA XƯỞNG (chỉ được dùng đúng các tên này cho trường \"the\"):\n" . implode(' | ', $the) . "\n\n"
             . "QUY TẮC GÁN THẺ:\n"
             . "- Gán theo nghĩa của chữ trong ô, kể cả viết tắt/viết hoa/sai chính tả nhẹ. Ví dụ: Tiếng Anh, T.A, Anh văn, English -> ENGLISH; Thể dục, GD thể chất, Giáo dục thể chất -> GDTC; "
             . "Tự nhiên và xã hội, TNXH -> TN & XH; Kĩ năng sống, KNS -> KN SỐNG (thẻ KĨ NĂNG chỉ khi ô ghi đúng 'Kĩ năng'); Mĩ thuật, Mỹ thuật, MT -> MĨ THUẬT; Âm nhạc, ÂN -> ÂM NHẠC; Đạo đức, ĐĐ -> ĐẠO ĐỨC; "
             . "Hoạt động trải nghiệm, HĐ trải nghiệm, HĐTN (kể cả 'HĐTN - SHL', 'HĐTN-CC') -> HĐTN; Chào cờ -> CHÀO CỜ; Sinh hoạt dưới cờ, SHDC -> SHDC; Sinh hoạt lớp, SHL, Sinh hoạt -> SH LỚP; "
             . "Luyện Toán, L.Toán -> L.TOÁN; Luyện Tiếng Việt, L.TV -> L.T.VIỆT; Ôn Toán -> ÔN TOÁN; Ôn Tiếng Việt, Ôn TV -> ÔN T.VIỆT; Rèn Toán -> R.TOÁN; Rèn Tiếng Việt -> R.T.VIỆT; Ôn tập -> ÔN TẬP; "
             . "Toán Tiếng Anh -> TOÁN TA; Khoa học Tiếng Anh -> KHTA; Tiếng Anh bản ngữ, TABN -> TABN; Lịch sử - Địa lí (chung một ô) -> LS & ĐL; Lịch sử (riêng) -> LỊCH SỬ; Địa lí (riêng) -> ĐỊA LÝ; "
             . "Khoa học tự nhiên -> KHTN; Giáo dục công dân -> GDCD; Giáo dục địa phương -> GDĐP; Công dân số -> CDS; Đọc sách, Đọc sách TV -> ĐỌC SÁCH; Stem, STEM+ -> STEM; Trông giữ -> TRÔNG GIỮ; Cờ, Cờ vua -> CỜ.\n"
             . "- Phân môn sau dấu hai chấm thuộc môn đó: 'Tiếng Việt: Đọc', 'T.Việt: Luyện tập' -> TIẾNG VIỆT.\n"
             . "- BỎ QUA tên giáo viên trong ô (trong ngoặc hoặc sau dấu '-'): 'GDTC (Trâm)' -> GDTC; 'TNXH - MIÊN' -> TN & XH.\n"
             . "- Ô không phải tiết học (Nghỉ, Ra chơi, Ăn trưa, Ngủ trưa) -> the = \"KHONG_PHAI_TIET\". Ô trống: bỏ, không liệt kê.\n"
             . "- Không có thẻ nào khớp -> the = \"KHAC\" và ghi chữ trong ô vào \"chu\" thật ngắn gọn (bỏ tên giáo viên).\n"
             . "- KHÔNG liệt kê: tiêu đề, dòng 'Thứ ...', cột Buổi/Sáng/Chiều, Tiết, Thời gian, ghi chú/chú thích ngoài lưới.\n"
             . "- Ô gộp trải nhiều tiết: liệt kê một lần cho MỖI tiết nó chiếm.\n"
             . "- Nếu là DANH SÁCH môn kèm số (không phải lưới): mỗi dòng một mục với so_luong = số ghi trên dòng.\n"
             . "- Đọc thật kỹ từng ô; gạch đỏ lượn sóng (lỗi chính tả của Word) KHÔNG phải chữ. Không bịa ô không có trong ảnh.\n"
             . "- la_tkb = false nếu ảnh không phải thời khoá biểu/danh sách môn (khi đó o = []).";
    }

    /** Khuôn JSON bắt buộc (enum = tên thẻ thư viện + KHAC + KHONG_PHAI_TIET). */
    public static function schema(array $the): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'la_tkb'  => ['type' => 'boolean'],
                'o'       => ['type' => 'array', 'items' => [
                    'type' => 'object',
                    'properties' => [
                        'chu'      => ['type' => 'string'],
                        'the'      => ['type' => 'string', 'enum' => array_values(array_merge($the, ['KHAC', 'KHONG_PHAI_TIET']))],
                        'so_luong' => ['type' => 'integer'],
                    ],
                    'required' => ['chu', 'the', 'so_luong'],
                    'additionalProperties' => false,
                ]],
            ],
            'required' => ['la_tkb', 'o'],
            'additionalProperties' => false,
        ];
    }
}
