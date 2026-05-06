<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('student_course_parts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_course_id')->constrained('student_courses')->cascadeOnDelete();
            $table->foreignId('course_part_id')->constrained('course_parts')->cascadeOnDelete();
            $table->float('credits')->default(0);
            $table->boolean('published')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('student_course_parts');
    }
};