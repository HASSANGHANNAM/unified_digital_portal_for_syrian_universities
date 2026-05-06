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