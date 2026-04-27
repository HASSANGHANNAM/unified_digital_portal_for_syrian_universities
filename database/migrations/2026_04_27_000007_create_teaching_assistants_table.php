<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teaching_assistants', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('ta_id_number')->unique();
            $table->foreignUuid('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->foreignUuid('supervisor_id')->nullable()->constrained('staff')->nullOnDelete();
            $table->date('assignment_date');
            $table->foreignUuid('person_id')->constrained('persons')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teaching_assistants');
    }
};