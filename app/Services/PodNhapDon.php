<?php
namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

/**
 * Đọc file đơn seller gửi (Excel .xlsx / .csv, vd "Simple 3D Plastic-xưởng Tuấn.xlsx") thành các dòng đơn.
 * Không cần thư viện: .xlsx = zip + XML (sheet đầu tiên). Dòng đầu có chữ = tiêu đề; cột nhận theo tên tiêu đề
 * (tiếng Việt/Anh, không phân biệt dấu): mã đơn, tên sản phẩm, số lượng, kích thước, chữ khắc, màu, ghi chú.
 */
class PodNhapDon
{
    /** Tên tiêu đề (đã bỏ dấu, chữ thường) -> cột. So khớp nguyên văn trước, rồi "chứa". */
    public const COT = [
        'ma'      => ['id', 'ma', 'ma don', 'ma don san', 'order', 'order id', 'order number', 'order no', 'so don', 'don hang'],
        'ten'     => ['name', 'ten', 'ten san pham', 'san pham', 'product', 'product name', 'item', 'item name', 'title', 'listing'],
        'sl'      => ['so luong', 'sl', 'qty', 'quantity'],
        'co'      => ['kich thuoc', 'size', 'co', 'variant', 'phan loai'],
        'chu'     => ['chu', 'chu khac', 'ten khac', 'ten in', 'khac ten', 'personalization', 'personalisation', 'custom', 'text'],
        'mau'     => ['mau', 'mau sac', 'color', 'colour'],
        'ghi_chu' => ['note', 'notes', 'ghi chu'],
    ];

    /**
     * @return array{cot: array<string,string>, dong: list<array{so:int, ma:string, ten:string, co:string, sl:int, chu:string, mau:string, ghi_chu:string}>}
     * @throws \InvalidArgumentException câu báo admin
     */
    public static function doc(UploadedFile $f): array
    {
        $duoi = strtolower($f->getClientOriginalExtension());
        $bang = match ($duoi) {
            'xlsx' => self::docXlsx($f->getRealPath()),
            'csv', 'txt' => self::docCsv($f->getRealPath()),
            default => throw new \InvalidArgumentException('Chỉ nhận file .xlsx hoặc .csv (Excel: Lưu thành .xlsx).'),
        };
        // dòng tiêu đề = dòng đầu tiên có ít nhất 2 ô chữ
        $dau = null;
        foreach ($bang as $i => $r) {
            if (count(array_filter($r, fn ($v) => trim((string) $v) !== '')) >= 2) { $dau = $i; break; }
        }
        if ($dau === null) throw new \InvalidArgumentException('File trống.');
        $tieuDe = array_map(fn ($v) => self::chuan((string) $v), $bang[$dau]);
        $cot = [];
        foreach (self::COT as $k => $ds) {
            foreach ($tieuDe as $j => $t) if ($t !== '' && in_array($t, $ds, true)) { $cot[$k] = $j; break; }
        }
        foreach (['ma', 'ten', 'sl'] as $k) {
            if (isset($cot[$k])) continue;
            foreach ($tieuDe as $j => $t) {
                if ($t === '' || in_array($j, $cot, true)) continue;
                foreach (self::COT[$k] as $mau) if (strlen($mau) > 2 && str_contains($t, $mau)) { $cot[$k] = $j; break 2; }
            }
        }
        if (!isset($cot['ten'])) {
            throw new \InvalidArgumentException('Không thấy cột tên sản phẩm (tiêu đề "name", "Tên sản phẩm", "Product"…). Tiêu đề đọc được: ' . implode(', ', array_filter($bang[$dau])) . '.');
        }
        $o = fn (array $r, string $k) => isset($cot[$k]) ? trim((string) ($r[$cot[$k]] ?? '')) : '';
        $dong = [];
        foreach (array_slice($bang, $dau + 1, null, true) as $i => $r) {
            $ten = $o($r, 'ten');
            if ($ten === '') continue;
            $sl = $o($r, 'sl');
            $dong[] = [
                'so' => $i + 1, 'ma' => mb_substr(self::so($o($r, 'ma')), 0, 60), 'ten' => mb_substr($ten, 0, 200),
                'co' => mb_substr($o($r, 'co'), 0, 60), 'sl' => $sl === '' ? 1 : (int) round((float) str_replace(',', '.', $sl)),
                'chu' => mb_substr($o($r, 'chu'), 0, 300), 'mau' => mb_substr($o($r, 'mau'), 0, 60), 'ghi_chu' => mb_substr($o($r, 'ghi_chu'), 0, 300),
            ];
        }
        if (!$dong) throw new \InvalidArgumentException('Không có dòng đơn nào (cột tên sản phẩm trống hết).');
        if (count($dong) > 500) throw new \InvalidArgumentException('Tối đa 500 dòng mỗi lần nhập.');
        return ['cot' => array_map(fn ($j) => (string) ($bang[$dau][$j] ?? ''), $cot), 'dong' => $dong];
    }

    /** Khoá khớp phân loại: tên sản phẩm + kích thước, chữ thường, gộp khoảng trắng. */
    public static function khoa(string $ten, string $co): string
    {
        $g = fn ($s) => mb_strtolower(preg_replace('/\s+/u', ' ', trim($s)));
        return mb_substr($g($ten) . ($co !== '' ? ' | ' . $g($co) : ''), 0, 250);
    }

    /** Bỏ dấu, chữ thường, gộp khoảng trắng — để so tiêu đề cột. */
    public static function chuan(string $s): string
    {
        return trim(preg_replace('/[^a-z0-9]+/', ' ', strtolower(Str::ascii($s))));
    }

    /** "127335.0" -> "127335" (Excel lưu mã đơn dạng số). */
    private static function so(string $v): string
    {
        return preg_match('/^\d+\.0+$/', $v) ? substr($v, 0, strpos($v, '.')) : $v;
    }

    /** @return list<list<string>> bảng chữ của sheet đầu tiên (ô trống = ''). */
    private static function docXlsx(string $duongDan): array
    {
        $z = new \ZipArchive();
        if ($z->open($duongDan) !== true) throw new \InvalidArgumentException('File .xlsx hỏng, không mở được.');
        $doc = function (string $ten) use ($z) {
            $x = $z->getFromName($ten);
            return $x === false ? null : simplexml_load_string($x, 'SimpleXMLElement', LIBXML_NONET | LIBXML_NOCDATA);
        };
        $dsChu = [];
        if ($ss = $doc('xl/sharedStrings.xml')) {
            foreach ($ss->si as $si) {
                $t = (string) $si->t;
                foreach ($si->r as $r) $t .= (string) $r->t;
                $dsChu[] = $t;
            }
        }
        // sheet đầu tiên theo workbook.xml + rels (không giả định sheet1.xml)
        $duong = 'xl/worksheets/sheet1.xml';
        $wb = $doc('xl/workbook.xml');
        $rels = $doc('xl/_rels/workbook.xml.rels');
        if ($wb && $rels && isset($wb->sheets->sheet[0])) {
            $rid = (string) ($wb->sheets->sheet[0]->attributes('http://schemas.openxmlformats.org/officeDocument/2006/relationships')['id'] ?? '');
            foreach ($rels->Relationship as $rel) {
                if ((string) $rel['Id'] === $rid) {
                    $t = ltrim((string) $rel['Target'], '/');
                    $duong = str_starts_with($t, 'xl/') ? $t : 'xl/' . $t;
                }
            }
        }
        $sh = $doc($duong);
        $z->close();
        if (!$sh) throw new \InvalidArgumentException('Không đọc được sheet đầu tiên của file .xlsx.');
        $bang = [];
        foreach ($sh->sheetData->row as $row) {
            $r = [];
            foreach ($row->c as $c) {
                $j = self::cot((string) $c['r']);
                $kieu = (string) $c['t'];
                $v = (string) $c->v;
                $r[$j] = match ($kieu) {
                    's' => $dsChu[(int) $v] ?? '',
                    'inlineStr' => (string) $c->is->t,
                    'b' => $v === '1' ? 'TRUE' : 'FALSE',
                    default => $v,
                };
            }
            if ($r) {
                $max = max(array_keys($r));
                $hang = [];
                for ($j = 0; $j <= $max; $j++) $hang[] = $r[$j] ?? '';
                $bang[((int) $row['r']) - 1] = $hang;
            }
        }
        ksort($bang);
        return $bang;
    }

    /** "AB12" -> 27 (chỉ số cột từ 0). */
    private static function cot(string $o): int
    {
        $n = 0;
        foreach (str_split(preg_replace('/[^A-Z]/', '', strtoupper($o))) as $ch) $n = $n * 26 + (ord($ch) - 64);
        return max(0, $n - 1);
    }

    /** @return list<list<string>> */
    private static function docCsv(string $duongDan): array
    {
        $txt = (string) file_get_contents($duongDan);
        $txt = preg_replace('/^\xEF\xBB\xBF/', '', $txt);
        if (!mb_check_encoding($txt, 'UTF-8')) $txt = mb_convert_encoding($txt, 'UTF-8', 'Windows-1252');
        $dauDong = strtok($txt, "\n") ?: '';
        $tach = collect([',', ';', "\t"])->sortByDesc(fn ($d) => substr_count($dauDong, $d))->first();
        $h = fopen('php://temp', 'r+');
        fwrite($h, $txt);
        rewind($h);
        $bang = [];
        while (($r = fgetcsv($h, 0, $tach, '"', '')) !== false) $bang[] = array_map(fn ($v) => (string) $v, $r);
        fclose($h);
        return $bang;
    }
}
