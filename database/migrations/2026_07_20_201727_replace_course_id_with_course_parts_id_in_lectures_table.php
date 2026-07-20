<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('lectures', function (Blueprint $table) {
            $table->foreignId('course_parts_id')
                ->nullable()
                ->constrained('course_parts')
                ->cascadeOnDelete();
            $table->dropForeign(['course_id']);
            $table->dropColumn('course_id');
        });
    }

    public function down()
    {
        Schema::table('lectures', function (Blueprint $table) {
            $table->foreignId('course_id')
                ->constrained('courses')
                ->cascadeOnDelete();
            $table->dropForeign(['course_parts_id']);
            $table->dropColumn('course_parts_id');
        });
    }
};
