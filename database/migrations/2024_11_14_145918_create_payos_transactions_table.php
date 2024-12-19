<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('payos_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('orderCode')->unique();
            $table->unsignedBigInteger('order_id');
            $table->decimal('amount', 11, 2)->default(0);
            $table->timestamps();

            //Add foreign key
            $table->foreign('order_id')->references('id')->on('orders');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('payos_transactions');
    }
};
