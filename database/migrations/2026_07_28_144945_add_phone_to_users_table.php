<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('nec_users', 'phone')) {
            Schema::table('nec_users', function (Blueprint $table) {
                $table->string('phone', 20)->nullable()->unique()->after('email');
            });
        }
    }

    public function down(): void
    {
        Schema::table('nec_users', function (Blueprint $table) {
            $table->dropColumn('phone');
        });
    }
};
