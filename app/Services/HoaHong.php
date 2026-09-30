<?php
namespace App\Services;

use App\Models\Affiliate;
use App\Models\DaiLy;
use App\Models\Don3d;
use App\Models\DonDiHo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Hoa hồng giới thiệu khu 3D — ví là bảng `affiliates` (total_earned/total_paid), rút qua `withdrawals`.
 *
 * Luật (chủ xưởng chốt 2026-09-30):
 *  - Đơn LẺ web 3D mua qua link ?ref=MÃ: người chia hưởng commission_rate % (mặc định 5) trên tiền hàng
 *    (tong − phi_ship). Cộng khi đơn "Hoàn tất"; rời Hoàn tất / xoá thì trừ lại.
 *  - Đơn ĐI HỘ của đại lý được giới thiệu: người giới thiệu hưởng rate_tuyen_duoi % (mặc định 3) trên tiền
 *    hàng (tong_si − thu_them). Cộng khi xưởng đánh dấu "Đã thu tiền"; bỏ đánh dấu / huỷ / xoá thì trừ lại.
 *  - CHỈ 1 CẤP. Không tự giới thiệu chính mình.
 *
 * Mọi thay đổi trạng thái/tiền của đơn gọi dongBo*() — hàm tự quyết cộng hay trừ, gọi lặp không sao
 * (cờ hoa_hong_da_cong + UPDATE có điều kiện nên bấm đúp / hai tab cùng lúc cũng không cộng hai lần).
 */
class HoaHong
{
    public const RATE_LE_MAC_DINH        = 5.00;
    public const RATE_TUYEN_DUOI_MAC_DINH = 3.00;
    public const RUT_TOI_THIEU           = 50000;

    /* ================= Đơn lẻ web 3D ================= */

    /** Đồng bộ hoa hồng đơn lẻ theo trạng thái hiện tại. $xoa=true khi sắp xoá đơn (gọi TRƯỚC khi delete). */
    public static function dongBoDon3d(Don3d $don, bool $xoa = false): void
    {
        $nen = !$xoa && $don->tt === 'hoan_tat' && $don->ref_code;
        if ($nen && !$don->hoa_hong_da_cong) {
            $aff = Affiliate::where('code', $don->ref_code)->where('is_active', true)->first();
            if (!$aff) return;
            $base = max(0, (int) $don->tong - (int) $don->phi_ship);
            $tien = (int) round($base * (float) $aff->commission_rate / 100);
            if ($tien > 0) self::cong($don, $aff, $tien);
        } elseif (!$nen && $don->hoa_hong_da_cong) {
            self::tru($don);
        }
    }

    /* ================= Đơn đi hộ (tuyến dưới) ================= */

    /** Đồng bộ hoa hồng tuyến dưới cho một đơn đi hộ. $xoa=true khi sắp xoá (gọi TRƯỚC khi delete). */
    public static function dongBoDiHo(DonDiHo $don, bool $xoa = false): void
    {
        $nen = !$xoa && $don->da_thanh_toan && $don->tt !== 'huy';
        if ($nen && !$don->hoa_hong_da_cong) {
            $dl = DaiLy::find($don->dai_ly_id);
            if (!$dl || !$dl->gioi_thieu_aff_id) return;
            if ($dl->affiliate_id && (int) $dl->affiliate_id === (int) $dl->gioi_thieu_aff_id) return; // tự giới thiệu
            $aff = Affiliate::whereKey($dl->gioi_thieu_aff_id)->where('is_active', true)->first();
            if (!$aff) return;
            $base = max(0, (int) $don->tong_si - (int) $don->thu_them);
            $tien = (int) round($base * (float) $aff->rate_tuyen_duoi / 100);
            if ($tien > 0) self::cong($don, $aff, $tien);
        } elseif (!$nen && $don->hoa_hong_da_cong) {
            self::tru($don);
        }
    }

    /** Tổng tiền đơn đã đổi (tính lại giá / thu thêm) -> tính lại hoa hồng nếu đã cộng. */
    public static function tinhLaiDiHo(DonDiHo $don): void
    {
        if ($don->hoa_hong_da_cong) { self::tru($don); $don->refresh(); }
        self::dongBoDiHo($don);
    }

    /* ================= Ví của đại lý ================= */

    /** Ví hoa hồng (affiliate) của một đại lý — tạo mới lần đầu. KHÔNG gộp với CTV trùng SĐT (tránh chiếm ví). */
    public static function viCuaDaiLy(DaiLy $dl): Affiliate
    {
        if ($dl->affiliate_id && ($a = Affiliate::find($dl->affiliate_id))) return $a;
        return DB::transaction(function () use ($dl) {
            $fresh = DaiLy::whereKey($dl->id)->lockForUpdate()->first();
            if ($fresh && $fresh->affiliate_id && ($a = Affiliate::find($fresh->affiliate_id))) {
                $dl->affiliate_id = $a->id;
                return $a;
            }
            $a = Affiliate::create([
                'name'            => (string) $dl->ten,
                'phone'           => (string) $dl->sdt,
                'code'            => self::taoMa($dl),
                'type'            => 'ctv',
                'commission_rate' => self::RATE_LE_MAC_DINH,
                'rate_tuyen_duoi' => self::RATE_TUYEN_DUOI_MAC_DINH,
                'is_active'       => true,
                'note'            => 'Ví hoa hồng tự tạo cho đại lý 3D #' . $dl->id,
            ]);
            DaiLy::whereKey($dl->id)->update(['affiliate_id' => $a->id]);
            $dl->affiliate_id = $a->id;
            return $a;
        });
    }

    /** Mã giới thiệu dễ đọc: DL + tên cuối (không dấu) + id, VD "DLGIANG3". */
    private static function taoMa(DaiLy $dl): string
    {
        $tu   = preg_split('/\s+/', trim(Str::ascii((string) $dl->ten))) ?: [];
        $cuoi = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', (string) end($tu)));
        $goc  = 'DL' . substr($cuoi !== '' ? $cuoi : 'DAILY', 0, 10) . $dl->id;
        $ma = $goc;
        for ($i = 0; $i < 20 && Affiliate::where('code', $ma)->exists(); $i++) {
            $ma = $goc . strtoupper(Str::random(2));
        }
        return $ma;
    }

    /** Tìm ví theo mã giới thiệu (không phân biệt hoa thường), chỉ ví đang hoạt động. */
    public static function viTheoMa(?string $ma): ?Affiliate
    {
        $ma = strtoupper(trim((string) $ma));
        if ($ma === '' || !preg_match('/^[A-Z0-9_\-]{3,40}$/', $ma)) return null;
        return Affiliate::where('code', $ma)->where('is_active', true)->first();
    }

    /* ================= Cộng / trừ nguyên tử ================= */

    private static function cong(Model $don, Affiliate $aff, int $tien): void
    {
        DB::transaction(function () use ($don, $aff, $tien) {
            $n = $don->newQuery()->whereKey($don->getKey())->where('hoa_hong_da_cong', false)
                ->update(['hoa_hong' => $tien, 'hoa_hong_aff_id' => $aff->id, 'hoa_hong_da_cong' => true]);
            if ($n !== 1) return; // đã có tiến trình khác cộng rồi
            Affiliate::whereKey($aff->id)->update([
                'total_earned' => DB::raw('total_earned + ' . $tien),
                'total_orders' => DB::raw('total_orders + 1'),
            ]);
        });
        $don->refresh();
    }

    private static function tru(Model $don): void
    {
        DB::transaction(function () use ($don) {
            $cur = $don->newQuery()->whereKey($don->getKey())->first();
            if (!$cur || !$cur->hoa_hong_da_cong) return;
            $n = $don->newQuery()->whereKey($don->getKey())->where('hoa_hong_da_cong', true)
                ->update(['hoa_hong' => 0, 'hoa_hong_da_cong' => false]);
            if ($n !== 1 || !$cur->hoa_hong_aff_id) return;
            $aff = Affiliate::find($cur->hoa_hong_aff_id);
            if (!$aff) return;
            $bot = min((int) $cur->hoa_hong, (int) $aff->total_earned);
            Affiliate::whereKey($aff->id)->update([
                'total_earned' => DB::raw('total_earned - ' . $bot),
                'total_orders' => DB::raw('CASE WHEN total_orders > 0 THEN total_orders - 1 ELSE 0 END'),
            ]);
        });
        if ($don->exists) $don->refresh();
    }
}
