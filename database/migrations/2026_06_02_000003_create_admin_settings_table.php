<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration {
    public function up(): void {
        Schema::create('admin_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });
        // Tài khoản mặc định: admin / dali2024
        DB::table('admin_settings')->insert([
            ['key' => 'admin_password', 'value' => Hash::make('dali2024'), 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'tg_token',       'value' => '',    'created_at' => now(), 'updated_at' => now()],
            ['key' => 'tg_chat_id',     'value' => '',    'created_at' => now(), 'updated_at' => now()],
            ['key' => 'bank_id',        'value' => 'VCB', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'bank_acc',       'value' => '',    'created_at' => now(), 'updated_at' => now()],
            ['key' => 'bank_name',      'value' => '',    'created_at' => now(), 'updated_at' => now()],
            ['key' => 'bank_label',     'value' => 'Vietcombank', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'shop_phone',     'value' => '0123456789', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'shop_address',   'value' => 'Số 1 Đường ABC, Hà Nội', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
    public function down(): void { Schema::dropIfExists('admin_settings'); }
};
