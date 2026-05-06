<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('teaching_assistants', function (Blueprint $table) {
            $table->id();
            $table->string('ta_id_number')->unique();
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->foreignId('supervisor_id')->nullable()->constrained('staff')->nullOnDelete();
            $table->date('assignment_date');
            $table->foreignId('person_id')->constrained('persons')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('teaching_assistants');
    }
};