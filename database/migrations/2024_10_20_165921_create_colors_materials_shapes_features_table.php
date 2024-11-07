<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('colors', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
        });

        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
        });

        Schema::create('shapes', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
        });

        Schema::create('features', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('colors');
        Schema::dropIfExists('materials');
        Schema::dropIfExists('shapes');
        Schema::dropIfExists('features');
    }
};
