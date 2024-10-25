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
        Schema::table('brands', function (Blueprint $table) {
            $table->unique('name');
        });
        Schema::table('colors', function (Blueprint $table) {
            $table->unique('name');
        });
        Schema::table('materials', function (Blueprint $table) {
            $table->unique('name');
        });
        Schema::table('shapes', function (Blueprint $table) {
            $table->unique('name');
        });
        Schema::table('features', function (Blueprint $table) {
            $table->unique('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('brands', function (Blueprint $table) {
            $table->string('name')->change();;
        });
        Schema::table('colors', function (Blueprint $table) {
            $table->string('name')->change();;
        });
        Schema::table('materials', function (Blueprint $table) {
            $table->string('name')->change();;
        });
        Schema::table('shapes', function (Blueprint $table) {
            $table->string('name')->change();;
        });
        Schema::table('features', function (Blueprint $table) {
            $table->string('name')->change();;
        });
    }
};
