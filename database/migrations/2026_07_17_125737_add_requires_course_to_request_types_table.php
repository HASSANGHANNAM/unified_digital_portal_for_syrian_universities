<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('request_types', function (Blueprint $table) {
            $table->boolean('requires_course')->default(false)->after('description');
        });
    }

    public function down()
    {
        Schema::table('request_types', function (Blueprint $table) {
            $table->dropColumn('requires_course');
        });
    }
};
