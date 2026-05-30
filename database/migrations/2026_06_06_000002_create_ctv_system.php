<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // Mật khẩu đăng nhập cho CTV
        Schema::table('affiliates', function (Blueprint $table) {
            $table->string('password')->nullable()->after('email');
        });

        // Yêu cầu rút tiền hoa hồng
        Schema::create('withdrawals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('affiliate_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('amount');                 // số tiền yêu cầu rút
            $table->string('bank_name')->nullable();              // snapshot thông tin NH lúc yêu cầu
            $table->string('bank_acc')->nullable();
            $table->string('bank_owner')->nullable();
            $table->enum('status', ['pending','approved','rejected'])->default('pending');
            $table->text('note')->nullable();                     // ghi chú của CTV / admin
            $table->timestamp('processed_at')->nullable();        // lúc admin xử lý
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('withdrawals');
        Schema::table('affiliates', function (Blueprint $table) {
            $table->dropColumn('password');
        });
    }
};
