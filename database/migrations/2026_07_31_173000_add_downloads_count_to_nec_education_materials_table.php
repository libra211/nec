<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('nec_education_materials', function (Blueprint $table) {
            $table->unsignedBigInteger('downloads_count')->default(0)->after('views');
        });
    }

    public function down(): void
    {
        Schema::table('nec_education_materials', function (Blueprint $table) {
            $table->dropColumn('downloads_count');
        });
    }
};
