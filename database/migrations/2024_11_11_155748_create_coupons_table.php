<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('coupons', function (Blueprint $table) {
            $table->string('code');
            $table->string('name');
            $table->integer('quantity')->default(0);
            $table->integer('discount_price');
            $table->enum('status', ['active', 'inactive'])->default('active');

            $table->primary('code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('coupons');
    }
};
