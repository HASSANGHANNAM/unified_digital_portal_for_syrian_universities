<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sanctions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sanction_type_id')->constrained('sanction_types')->cascadeOnDelete();
            $table->enum('status', ['expired', 'ongoing', 'مطعون', 'مناقشة الطعن']);
            $table->date('issued_date');
            $table->date('expiry_date')->nullable();
            $table->text('notes')->nullable();
            $table->text('student_response')->nullable();
            $table->text('staff_response')->nullable();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('staff_id')->constrained('staff')->cascadeOnDelete();
            $table->foreignId('course_id')->nullable()->constrained('courses')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sanctions');
    }
};