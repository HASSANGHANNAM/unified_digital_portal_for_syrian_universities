<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('student_courses', function (Blueprint $table) {
            $table->string('academic_year', 9)
                ->nullable()
                ->after('status');

            $table->unsignedTinyInteger('semester')
                ->nullable()
                ->after('academic_year');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_courses', function (Blueprint $table) {
            $table->dropColumn([
                'academic_year',
                'semester'
            ]);
        });
    }
};
