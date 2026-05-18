<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('schedule_group', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schedule_id')->constrained('schedules','id')->cascadeOnDelete();
            $table->foreignId('group_id')->constrained('groups','id')->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('schedule_group');
    }
};
