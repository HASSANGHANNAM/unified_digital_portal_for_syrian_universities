<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lectures', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->string('file_url')->nullable();
            $table->dateTime('upload_date')->nullable();
            $table->string('type')->nullable();
            $table->integer('order_index')->default(0);
            $table->foreignUuid('course_id')->constrained('courses')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lectures');
    }
};