<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('request_media', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['image', 'video', 'pdf', 'excel', 'word', 'etc']);
            $table->foreignId('request_id')->constrained('requests')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('request_media');
    }
};