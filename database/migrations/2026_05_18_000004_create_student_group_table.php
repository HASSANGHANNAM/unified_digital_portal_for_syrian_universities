<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('student_group', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students','id')->cascadeOnDelete();
            $table->foreignId('group_id')->constrained('groups','id')->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('student_group');
    }
};
