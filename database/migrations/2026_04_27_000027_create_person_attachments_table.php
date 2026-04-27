<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('person_attachments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('path');
            $table->foreignUuid('person_id')->constrained('persons')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('person_attachments');
    }
};