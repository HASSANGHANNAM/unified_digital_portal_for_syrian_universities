<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('request_type_availability', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_available')->default(false);
            $table->foreignId('request_type_id')->constrained('request_types')->cascadeOnDelete();
            $table->foreignId('college_id')->constrained('colleges')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('request_type_availability');
    }
};