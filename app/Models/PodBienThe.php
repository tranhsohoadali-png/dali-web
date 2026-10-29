<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Phân loại của một mẫu POD (kiểu, cỡ) + số gram trên slicer. Bảng pod_bien_the. */
class PodBienThe extends Model
{
    protected $table = 'pod_bien_the';
    protected $guarded = [];
    protected $hidden = ['file_in']; // đường dẫn file in chỉ admin thấy
    protected $casts = ['gram' => 'float', 'kich_thuoc' => 'array'];

    public function mau()
    {
        return $this->belongsTo(PodMau::class, 'mau_id');
    }

    /** Danh sách màu cho phép (mảng), rỗng = không bắt chọn. */
    public function dsMau(): array
    {
        return array_values(array_filter(array_map('trim', explode(',', (string) $this->mau_sac)), fn ($m) => $m !== ''));
    }

    /** "76,2 × 87,7 × 3" (rộng × cao × dày, mm) cho ô nhập admin; rỗng nếu cỡ chưa có số đo cố định. */
    public function chuoiKichThuoc(): string
    {
        $k = $this->kich_thuoc;
        if (!is_array($k) || !isset($k['rong'], $k['cao'], $k['day'])) return '';
        return implode(' × ', array_map(fn ($x) => str_replace('.', ',', rtrim(rtrim(number_format((float) $x, 1, '.', ''), '0'), '.')), [$k['rong'], $k['cao'], $k['day']]));
    }

    /** Đọc "76,2 × 87,7 × 3" / "76.2x87.7x3" -> ['rong','cao','day'] mm; sai dạng -> null. */
    public static function docKichThuoc(?string $s): ?array
    {
        $so = preg_split('/\s*[x×*]\s*/iu', str_replace(',', '.', trim((string) $s)));
        if (count($so) !== 3 || array_filter($so, fn ($x) => !is_numeric($x) || (float) $x <= 0 || (float) $x > 2000)) return null;
        return ['rong' => round((float) $so[0], 1), 'cao' => round((float) $so[1], 1), 'day' => round((float) $so[2], 2)];
    }
}
