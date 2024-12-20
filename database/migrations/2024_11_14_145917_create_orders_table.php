<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->timestamp('date');
            $table->unsignedBigInteger('branch_id');
            $table->string('address');
            $table->string('note', 1000)->nullable();
            $table->unsignedBigInteger('coupon_id')->nullable();
            $table->decimal('total_price', 11, 2)->default(0);

            $table->enum('order_status', ['Đang xử lý', 'Đã xử lý và sẵn sàng giao hàng', 'Đang giao hàng', 'Đã giao', 'Đã hủy'])->default('Đang xử lý');
            $table->enum('payment_status', ['Chưa thanh toán', 'Đã thanh toán'])->default('Chưa thanh toán');

            $table->enum('payment_method', ['Chuyển khoản', 'Tiền mặt'])->default('Chuyển khoản');

            $table->enum('status', ['active', 'inactive'])->default('active');

            //Add foreign key
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('branch_id')->references('id')->on('branches');
            $table->foreign('coupon_id')->references('id')->on('coupons');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('orders');
    }
};
