<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('request_types', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('description')->nullable();
            $table->boolean('university_director_acceptance')->default(false);
            $table->boolean('college_dean_acceptance')->default(false);
            $table->boolean('department_head_acceptance')->default(false);
            $table->boolean('student_stuff_acceptance')->default(false);
            $table->boolean('exams_stuff_acceptance')->default(false);
            $table->boolean('doctor_acceptance')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('request_types');
    }
};