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
        Schema::create('color', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        Schema::create('material', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        Schema::create('shape', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        Schema::create('feature', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('color');
        Schema::dropIfExists('material');
        Schema::dropIfExists('shape');
        Schema::dropIfExists('feature');
    }
};
