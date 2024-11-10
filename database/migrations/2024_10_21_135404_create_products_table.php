<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description', 1000);
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('brand_id');
            $table->unsignedBigInteger('material_id');
            $table->unsignedBigInteger('shape_id');
            $table->enum('gender', ['male', 'female', 'unisex'])->default('unisex');
            $table->decimal('price', 10, 2);
            $table->timestamp('created_time');
            $table->enum('status', ['active', 'inactive'])->default('active');

            //Add foreign key
            $table->foreign('category_id')->references('id')->on('category');
            $table->foreign('brand_id')->references('id')->on('brands');
            $table->foreign('material_id')->references('id')->on('materials');
            $table->foreign('shape_id')->references('id')->on('shapes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('products');
    }
};
