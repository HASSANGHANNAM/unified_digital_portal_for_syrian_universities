<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('request_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('role');
            $table->foreignId('user_signature_id')->nullable()->constrained('user_signatures')->onDelete('set null');
            $table->foreignId('request_id')->constrained('requests')->onDelete('cascade');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamp('signed_at')->nullable();
            $table->timestamps();
            $table->index(['request_id', 'user_id']);
            $table->index(['user_id', 'status']);
            $table->index('signed_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('request_user');
    }
};
