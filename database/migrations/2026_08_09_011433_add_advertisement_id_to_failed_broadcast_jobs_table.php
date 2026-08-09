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
        Schema::table('failed_broadcast_jobs', function (Blueprint $table) {
            $table->unsignedBigInteger('advertisement_id')->nullable()->after('type');
        });
    }

    public function down(): void
    {
        Schema::table('failed_broadcast_jobs', function (Blueprint $table) {
            $table->dropColumn('advertisement_id');
        });
    }
};
