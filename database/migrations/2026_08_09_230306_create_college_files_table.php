<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('college_files', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type');
            $table->string('path');
            $table->integer('upload_year');
            $table->integer('student_year');
            $table->integer('student_semester');
            $table->foreignId('college_id')->constrained('colleges')->cascadeOnDelete();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['college_id', 'upload_year', 'student_year', 'student_semester'], 'cf_coll_yr_sem_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('college_files');
    }
};
