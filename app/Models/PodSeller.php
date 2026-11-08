<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/** Tài khoản seller POD (cổng /pod). Xưởng cấp tài khoản, seller không tự đăng ký. Bảng pod_seller. */
class PodSeller extends Model
{
    protected $table = 'pod_seller';
    protected $guarded = [];
    protected $hidden = ['matkhau'];
    protected $casts = [
        'hien'          => 'boolean',
        'cho_duyet'     => 'boolean',
        'dong_y_luc'    => 'datetime',
        'dang_nhap_luc' => 'datetime',
        'doi_mk_luc'    => 'datetime',
    ];

    public function nhanVien()
    {
        return $this->hasMany(PodNhanVien::class, 'seller_id');
    }

    /**
     * Số điện thoại đã là tên đăng nhập của ai chưa — seller HOẶC nhân viên (cùng một màn đăng nhập nên không được trùng).
     * $boSeller / $boNv: bỏ qua chính tài khoản đang sửa.
     */
    public static function sdtDaDung(string $sdt, ?int $boSeller = null, ?int $boNv = null): bool
    {
        return static::where('sdt', $sdt)->when($boSeller, fn ($q) => $q->where('id', '!=', $boSeller))->exists()
            || PodNhanVien::where('sdt', $sdt)->when($boNv, fn ($q) => $q->where('id', '!=', $boNv))->exists();
    }

    /** Số dư ví = tổng mọi dòng sổ ví. */
    public function soDu(): int
    {
        return (int) DB::table('pod_vi')->where('seller_id', $this->id)->sum('so_tien');
    }

    /** Còn dùng được = số dư + hạn mức trả sau (pod_seller.han_muc, 0 = trả trước). Âm khi xưởng hạ hạn mức dưới số nợ. */
    public function khaDung(): int
    {
        return $this->soDu() + (int) $this->han_muc;
    }

    /** Mã seller 6 ký tự, bỏ chữ dễ nhầm (0/O, 1/I/L) vì seller gõ tay vào nội dung chuyển khoản. */
    public static function taoMa(): string
    {
        $chu = 'ABCDEFGHJKMNPQRSTUVWXYZ23456789';
        do {
            $ma = '';
            for ($i = 0; $i < 6; $i++) $ma .= $chu[random_int(0, strlen($chu) - 1)];
        } while (static::where('ma', $ma)->exists());
        return $ma;
    }
}
