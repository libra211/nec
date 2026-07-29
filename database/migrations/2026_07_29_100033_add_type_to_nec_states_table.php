<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('nec_states', function (Blueprint $table) {
            $table->enum('type', ['state', 'admin_area'])->default('state')->after('code');
        });
    }

    public function down(): void
    {
        Schema::table('nec_states', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
