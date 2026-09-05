<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('nec_observers') && !Schema::hasColumn('nec_observers', 'assigned_state')) {
            Schema::table('nec_observers', function (Blueprint $table) {
                $table->string('assigned_state', 100)->nullable()->after('nationality')->index();
            });
        }
        if (Schema::hasTable('nec_observer_applications') && !Schema::hasColumn('nec_observer_applications', 'assigned_state')) {
            Schema::table('nec_observer_applications', function (Blueprint $table) {
                $table->string('assigned_state', 100)->nullable()->after('observer_count');
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('nec_observers') && Schema::hasColumn('nec_observers', 'assigned_state')) {
            Schema::table('nec_observers', function (Blueprint $table) {
                $table->dropColumn('assigned_state');
            });
        }
        if (Schema::hasTable('nec_observer_applications') && Schema::hasColumn('nec_observer_applications', 'assigned_state')) {
            Schema::table('nec_observer_applications', function (Blueprint $table) {
                $table->dropColumn('assigned_state');
            });
        }
    }
};