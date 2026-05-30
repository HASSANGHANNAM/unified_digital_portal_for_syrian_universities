<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('request_type_availability', function (Blueprint $table) {
            $table->index(['college_id', 'is_available'], 'rta_college_is_available_index');
        });
    }

    public function down()
    {
        Schema::table('request_type_availability', function (Blueprint $table) {
            $table->dropIndex('rta_college_is_available_index');
        });
    }
};
