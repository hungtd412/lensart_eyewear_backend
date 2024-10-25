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
        Schema::create('colors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        Schema::create('shapes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        Schema::create('features', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('colors');
        Schema::dropIfExists('materials');
        Schema::dropIfExists('shapes');
        Schema::dropIfExists('features');
    }
};
