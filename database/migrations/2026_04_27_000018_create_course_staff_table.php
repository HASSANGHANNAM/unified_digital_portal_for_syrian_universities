<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('course_staff', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->foreignId('ta_id')->nullable()->constrained('teaching_assistants')->nullOnDelete();
            $table->foreignId('doctor_id')->nullable()->constrained('doctors')->nullOnDelete();
            $table->foreignId('staff_id')->nullable()->constrained('staff')->nullOnDelete(); // كان مكتوب credits في الرسم لكن الصواب staff
            $table->boolean('is_advisor')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('course_staff');
    }
};