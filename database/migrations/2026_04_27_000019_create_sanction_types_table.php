<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sanction_types', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('reason');
            $table->string('name');
            $table->integer('years')->default(0);
            $table->integer('months')->default(0);
            $table->integer('days')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sanction_types');
    }
};