<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('department_heads', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('department_id')->constrained('departments')->cascadeOnDelete();
            $table->foreignUuid('doctor_id')->constrained('doctors')->cascadeOnDelete();
            $table->date('hired_date');
            $table->date('expire_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('department_heads');
    }
};