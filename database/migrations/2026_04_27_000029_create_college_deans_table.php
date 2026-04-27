<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('college_deans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('college_id')->constrained('colleges')->cascadeOnDelete();
            $table->foreignUuid('doctor_id')->constrained('doctors')->cascadeOnDelete();
            $table->date('hired_date');
            $table->date('expire_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('college_deans');
    }
};