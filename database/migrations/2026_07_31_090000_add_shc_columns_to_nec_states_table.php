<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('nec_states', function (Blueprint $table) {
            $table->string('shc_chairperson')->nullable()->after('capital');
            $table->string('shc_email')->nullable()->after('shc_chairperson');
        });
    }

    public function down()
    {
        Schema::table('nec_states', function (Blueprint $table) {
            $table->dropColumn(['shc_chairperson', 'shc_email']);
        });
    }
};
