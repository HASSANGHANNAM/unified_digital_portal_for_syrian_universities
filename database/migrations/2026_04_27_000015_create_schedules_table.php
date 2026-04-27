<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('day_of_week');
            $table->string('start_time');
            $table->string('end_time');
            $table->string('location');
            $table->string('semester');
            $table->integer('year');
            $table->foreignUuid('course_id')->constrained('courses')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};