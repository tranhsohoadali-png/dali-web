<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'vtp_order_number')) $table->string('vtp_order_number')->nullable()->after('status');
            if (!Schema::hasColumn('orders', 'vtp_status'))       $table->integer('vtp_status')->nullable()->after('vtp_order_number');
            if (!Schema::hasColumn('orders', 'vtp_status_name'))  $table->string('vtp_status_name')->nullable()->after('vtp_status');
            if (!Schema::hasColumn('orders', 'vtp_status_at'))    $table->timestamp('vtp_status_at')->nullable()->after('vtp_status_name');
            if (!Schema::hasColumn('orders', 'vtp_service'))      $table->string('vtp_service')->nullable()->after('vtp_status_at');
            if (!Schema::hasColumn('orders', 'weight'))           $table->integer('weight')->nullable()->after('vtp_service');
        });

        // Bổ sung các key cài đặt còn thiếu cho Viettel Post
        $keys = [
            'vtp_enabled'        => '0',
            'vtp_token'          => '',
            'vtp_env'            => 'prod',          // prod | dev
            'vtp_sender_name'    => 'DALI',
            'vtp_sender_phone'   => '',
            'vtp_sender_address' => '',
            'vtp_service'        => 'VCN',           // mã dịch vụ mặc định
            'vtp_webhook_token'  => '',              // secret để xác thực webhook
            'default_weight'     => '500',           // gram/đơn nếu chưa nhập
        ];
        foreach ($keys as $k => $v) {
            $exists = DB::table('admin_settings')->where('key', $k)->exists();
            if (!$exists) {
                DB::table('admin_settings')->insert(['key' => $k, 'value' => $v]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['vtp_order_number','vtp_status','vtp_status_name','vtp_status_at','vtp_service','weight']);
        });
    }
};
