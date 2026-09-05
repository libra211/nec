<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('nec_voters', function (Blueprint $table) {
            if (!Schema::hasColumn('nec_voters', 'eligibility_date')) {
                $table->date('eligibility_date')->nullable()->after('dob');
            }
            if (!Schema::hasColumn('nec_voters', 'eligible_to_vote')) {
                $table->boolean('eligible_to_vote')->default(false)->index()->after('status');
            }
            if (!Schema::hasColumn('nec_voters', 'pre_registered')) {
                $table->boolean('pre_registered')->default(false)->index()->after('eligible_to_vote');
            }
        });
    }

    public function down(): void
    {
        Schema::table('nec_voters', function (Blueprint $table) {
            foreach (['eligibility_date', 'eligible_to_vote', 'pre_registered'] as $col) {
                if (Schema::hasColumn('nec_voters', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};