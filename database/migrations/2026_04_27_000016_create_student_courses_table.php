<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('student_courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->float('credits')->default(0);
            $table->enum('status', ['pass', 'fail', 'helped pass',"in_progress"]);
            $table->string('academic_year', 9)
                ->nullable();
            $table->unsignedTinyInteger('semester')
                ->nullable();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->timestamps();
        });
    }



    public function down()
    {
        Schema::dropIfExists('student_courses');
    }
};
