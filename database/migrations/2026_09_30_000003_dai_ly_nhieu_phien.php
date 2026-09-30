<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Đại lý đăng nhập NHIỀU THIẾT BỊ cùng lúc: mỗi lần đăng nhập = 1 dòng phiên.
 * Chỉ lưu băm SHA-256 của token (lộ DB cũng không dùng lại được phiên).
 * Phiên đang có ở cột dai_ly.token được chuyển sang -> không ai bị đăng xuất khi nâng cấp.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('dai_ly_phien')) {
            Schema::create('dai_ly_phien', function (Blueprint $t) {
                $t->id();
                $t->unsignedBigInteger('dai_ly_id')->index();
                $t->string('token_hash', 64)->unique();
                $t->string('thiet_bi', 120)->nullable();   // user-agent rút gọn
                $t->string('ip', 45)->nullable();
                $t->timestamp('dung_luc')->nullable();      // lần dùng gần nhất (ghi thưa, ~10 phút/lần)
                $t->timestamps();
            });
        }
        $now = now();
        foreach (DB::table('dai_ly')->whereNotNull('token')->where('token', '!=', '')->get(['id', 'token', 'dang_nhap_luc']) as $d) {
            DB::table('dai_ly_phien')->insertOrIgnore([
                'dai_ly_id'  => $d->id,
                'token_hash' => hash('sha256', $d->token),
                'thiet_bi'   => 'Phiên cũ (trước khi cho nhiều thiết bị)',
                'dung_luc'   => $d->dang_nhap_luc ?? $now,
                'created_at' => $d->dang_nhap_luc ?? $now,
                'updated_at' => $now,
            ]);
        }
        DB::table('dai_ly')->update(['token' => null]); // cột cũ thôi dùng
    }

    public function down(): void
    {
        Schema::dropIfExists('dai_ly_phien');
    }
};
