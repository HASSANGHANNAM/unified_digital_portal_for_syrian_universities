<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('failed_broadcast_jobs', function (Blueprint $table) {
            $table->id();
            $table->json('user_ids');
            $table->string('title');
            $table->text('message');
            $table->string('type');
            $table->unsignedInteger('attempts')->default(1);
            $table->timestamp('last_attempt_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('failed_broadcast_jobs');
    }
};
