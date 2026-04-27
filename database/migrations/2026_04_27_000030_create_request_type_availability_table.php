<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('request_type_availability', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->boolean('is_available')->default(false);
            $table->foreignUuid('request_type_id')->constrained('request_types')->cascadeOnDelete();
            $table->foreignUuid('college_id')->constrained('colleges')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('request_type_availability');
    }
};