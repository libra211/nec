<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('nec_agents', 'agent_code')) {
            Schema::table('nec_agents', function (Blueprint $table) {
                $table->string('agent_code', 30)->nullable()->unique()->after('national_id');
            });
        }

        if (!Schema::hasColumn('nec_voters', 'registered_by_code')) {
            Schema::table('nec_voters', function (Blueprint $table) {
                $table->string('registered_by_code', 30)->nullable()->after('registered_by_name');
            });
        }

        $codes = DB::table('nec_states')->pluck('code', 'name');

        foreach (DB::table('nec_agents')->orderBy('id')->get() as $agent) {
            $area = $codes[$agent->assigned_state] ?? $codes[$agent->state] ?? 'NAT';
            $seq = str_pad((string) $agent->id, 3, '0', STR_PAD_LEFT);
            $code = strtoupper($area) . '-' . $seq;
            DB::table('nec_agents')->where('id', $agent->id)->update(['agent_code' => $code]);
        }

        DB::table('nec_voters')
            ->join('nec_agents', 'nec_agents.id', '=', 'nec_voters.registered_by_user_id')
            ->where('nec_voters.registration_type', 'agent')
            ->whereNotNull('nec_voters.registered_by_user_id')
            ->update(['nec_voters.registered_by_code' => DB::raw('nec_agents.agent_code')]);
    }

    public function down()
    {
        Schema::table('nec_agents', function (Blueprint $table) {
            $table->dropUnique(['agent_code']);
            $table->dropColumn('agent_code');
        });

        Schema::table('nec_voters', function (Blueprint $table) {
            $table->dropColumn('registered_by_code');
        });
    }
};