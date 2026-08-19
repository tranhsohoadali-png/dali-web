<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Phân loại hàng kiểu Shopee cho sản phẩm 3D.
 *
 * Thêm cột `variant_groups` (JSON) chỉ để DỰNG LẠI trình sửa (nhóm + tùy chọn +
 * bảng giá/kho). Nguồn sự thật cho checkout vẫn là mảng phẳng `variants` —
 * cột này KHÔNG đụng tới nó. Mỗi lần lưu, controller biên dịch
 * variant_groups -> variants theo thứ tự cố định (row index == variantIndex),
 * nên web khách + Api3dController::priceCart không phải sửa gì.
 *
 * Backfill TỔNG QUÁT (không hardcode từng sản phẩm): với mọi sản phẩm đang có
 * variants, dựng 1 nhóm "Chọn phiên bản" gồm đúng các tùy chọn hiện tại, GIỮ
 * nguyên giá/thứ tự. Sản phẩm không có variants -> nhóm rỗng. An toàn tuyệt đối:
 * không sản phẩm nào đổi giá.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('sp_3d', 'variant_groups')) {
            Schema::table('sp_3d', function (Blueprint $t) {
                $t->json('variant_groups')->nullable()->after('variants');
            });
        }

        foreach (DB::table('sp_3d')->get(['id', 'variants']) as $sp) {
            $variants = json_decode((string) $sp->variants, true);
            $vg = ['groups' => [], 'rows' => []];

            if (is_array($variants) && count($variants)) {
                $options = [];
                $rows = [];
                foreach (array_values($variants) as $i => $v) {
                    $ten = (string) ($v['ten'] ?? '');
                    $options[] = $ten;
                    $rows[] = [
                        'combo' => [$i],
                        'ten'   => $ten,
                        'gia'   => (int) ($v['gia'] ?? 0),
                        'kho'   => null,
                    ];
                }
                $vg = [
                    'groups' => [['ten' => 'Chọn phiên bản', 'options' => $options]],
                    'rows'   => $rows,
                ];
            }

            DB::table('sp_3d')->where('id', $sp->id)->update([
                'variant_groups' => json_encode($vg, JSON_UNESCAPED_UNICODE),
                // variants GIỮ NGUYÊN — không ghi lại, tránh mọi rủi ro lệch giá.
            ]);
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('sp_3d', 'variant_groups')) {
            Schema::table('sp_3d', function (Blueprint $t) {
                $t->dropColumn('variant_groups');
            });
        }
    }
};
