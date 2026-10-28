<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Phân loại của một mẫu POD (kiểu, cỡ) + số gram trên slicer. Bảng pod_bien_the. */
class PodBienThe extends Model
{
    protected $table = 'pod_bien_the';
    protected $guarded = [];
    protected $hidden = ['file_in']; // đường dẫn file in chỉ admin thấy
    protected $casts = ['gram' => 'float'];

    public function mau()
    {
        return $this->belongsTo(PodMau::class, 'mau_id');
    }

    /** Danh sách màu cho phép (mảng), rỗng = không bắt chọn. */
    public function dsMau(): array
    {
        return array_values(array_filter(array_map('trim', explode(',', (string) $this->mau_sac)), fn ($m) => $m !== ''));
    }
}
