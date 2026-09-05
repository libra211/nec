<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('nec_voters', function (Blueprint $table) {
            if (!Schema::hasColumn('nec_voters', 'deceased_date')) {
                $table->date('deceased_date')->nullable()->after('pre_registered');
            }
            if (!Schema::hasColumn('nec_voters', 'deceased_at')) {
                $table->timestamp('deceased_at')->nullable()->after('deceased_date');
            }
            if (!Schema::hasColumn('nec_voters', 'deceased_by')) {
                $table->string('deceased_by', 150)->nullable()->after('deceased_at');
            }
            if (!Schema::hasColumn('nec_voters', 'death_certificate_ref')) {
                $table->string('death_certificate_ref', 100)->nullable()->after('deceased_by');
            }
        });

        DB::statement("ALTER TABLE `nec_voters` MODIFY `status` ENUM('active','inactive','suspended','trash','deceased') NOT NULL DEFAULT 'active'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE `nec_voters` MODIFY `status` ENUM('active','inactive','suspended','trash') NOT NULL DEFAULT 'active'");

        Schema::table('nec_voters', function (Blueprint $table) {
            foreach (['deceased_date', 'deceased_at', 'deceased_by', 'death_certificate_ref'] as $col) {
                if (Schema::hasColumn('nec_voters', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};