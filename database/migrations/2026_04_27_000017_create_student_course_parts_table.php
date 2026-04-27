<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_course_parts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('student_course_id')->constrained('student_courses')->cascadeOnDelete();
            $table->foreignUuid('course_part_id')->constrained('course_parts')->cascadeOnDelete();
            $table->float('credits')->default(0);
            $table->boolean('published')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_course_parts');
    }
};