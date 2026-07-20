<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_type_id')->constrained('request_types')->cascadeOnDelete();
            $table->text('reason');
            $table->dateTime('submission_date');
            $table->enum('status', [
                'pending',
                'generating_doctor_pdf',
                'waiting_doctor',
                'generating_exams_stuff_pdf',
                'waiting_exams_stuff',
                'generating_student_stuff_pdf',
                'waiting_student_stuff',
                'generating_department_head_pdf',
                'waiting_department_head',
                'generating_college_dean_pdf',
                'waiting_college_dean',
                'generating_university_director_pdf',
                'waiting_university_director',
                'completed',
                'rejected',
                'cancelled'
            ])->default('pending');
            $table->dateTime('decision_date')->nullable();
            $table->text('decision_reason')->nullable();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('processed_by_staff_id')->nullable()->constrained('staff')->nullOnDelete();
            $table->foreignId('course_id')->nullable()->constrained('courses')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('requests');
    }
};
