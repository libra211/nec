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
        Schema::table('nec_political_parties', function (Blueprint $table) {
            $table->string('registration_document', 500)->nullable()->after('logo');
        });
    }

    public function down(): void
    {
        Schema::table('nec_political_parties', function (Blueprint $table) {
            $table->dropColumn('registration_document');
        });
    }
};
