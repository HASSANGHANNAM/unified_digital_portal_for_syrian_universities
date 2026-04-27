<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('staff', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('staff_id_number')->unique();
            $table->foreignUuid('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->date('hire_date');
            $table->string('employment_status');
            $table->foreignUuid('person_id')->constrained('persons')->cascadeOnDelete();
            $table->timestamps();
        });

        // ربط dean_id بعد أن أصبح جدول staff موجوداً
        Schema::table('colleges', function (Blueprint $table) {
            $table->foreign('dean_id')->references('id')->on('staff')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('colleges', function (Blueprint $table) {
            $table->dropForeign(['dean_id']);
        });
        Schema::dropIfExists('staff');
    }
};