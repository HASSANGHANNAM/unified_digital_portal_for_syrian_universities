<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_staff', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('course_id')->constrained('courses')->cascadeOnDelete();
            $table->foreignUuid('ta_id')->nullable()->constrained('teaching_assistants')->nullOnDelete();
            $table->foreignUuid('doctor_id')->nullable()->constrained('doctors')->nullOnDelete();
            $table->foreignUuid('staff_id')->nullable()->constrained('staff')->nullOnDelete(); // كان مكتوب credits في الرسم لكن الصواب staff
            $table->boolean('is_advisor')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_staff');
    }
};