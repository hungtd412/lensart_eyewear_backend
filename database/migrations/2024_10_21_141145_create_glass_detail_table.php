<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('glass_detail', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('color_id');
            $table->unsignedBigInteger('material_id');
            $table->unsignedBigInteger('shape_id');
            $table->enum('gender', ['male', 'female']);
            $table->integer('quantity')->default(0);
            $table->decimal('price', 10, 2);

            //Add foreign key
            $table->foreign('product_id')->references('id')->on('product');
            $table->foreign('branch_id')->references('id')->on('branch');
            $table->foreign('color_id')->references('id')->on('color');
            $table->foreign('shape_id')->references('id')->on('shape');
            $table->foreign('material_id')->references('id')->on('material');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('glass_detail');
    }
};
