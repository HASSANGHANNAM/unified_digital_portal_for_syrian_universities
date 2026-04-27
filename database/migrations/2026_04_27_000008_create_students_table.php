<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('student_id_number')->unique();
            $table->string('academic_status');
            $table->string('major');
            $table->integer('enrollment_year');
            $table->float('current_gpa')->nullable();
            $table->string('advisor_id')->nullable();
            $table->foreignUuid('person_id')->constrained('persons')->cascadeOnDelete();
            $table->foreignUuid('college_id')->constrained('colleges')->cascadeOnDelete();
            $table->foreignUuid('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};