<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Thêm 'Crypto' vào enum payment_method
        DB::statement("ALTER TABLE orders MODIFY COLUMN payment_method ENUM('Chuyển khoản', 'Tiền mặt', 'Crypto') DEFAULT 'Chuyển khoản'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Xóa 'Crypto' khỏi enum payment_method
        // Lưu ý: Cần kiểm tra và cập nhật các bản ghi có payment_method = 'Crypto' trước khi rollback
        DB::statement("ALTER TABLE orders MODIFY COLUMN payment_method ENUM('Chuyển khoản', 'Tiền mặt') DEFAULT 'Chuyển khoản'");
    }
};
