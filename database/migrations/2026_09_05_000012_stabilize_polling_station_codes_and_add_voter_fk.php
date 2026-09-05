<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Build deterministic polling station codes: {STATE_CODE}-PS-{NNN}
        $stations = DB::table('nec_polling_stations')->orderBy('id')->get();
        $stationCodes = [];
        $stateCounters = [];

        foreach ($stations as $station) {
            $stateCode = DB::table('nec_states')
                ->where('name', $station->state)
                ->value('code') ?? 'UNK';

            $stateCounters[$stateCode] = ($stateCounters[$stateCode] ?? 0) + 1;
            $seq = str_pad($stateCounters[$stateCode], 3, '0', STR_PAD_LEFT);
            $code = "{$stateCode}-PS-{$seq}";
            $stationCodes[$station->id] = $code;

            DB::table('nec_polling_stations')->where('id', $station->id)->update(['code' => $code]);
        }

        // 2. Add polling_station_id FK to nec_voters
        if (!Schema::hasColumn('nec_voters', 'polling_station_id')) {
            Schema::table('nec_voters', function (Blueprint $table) {
                $table->unsignedBigInteger('polling_station_id')->nullable()->index()->after('polling_station');
            });
        }

        // 3. Backfill polling_station_id by matching voters.polling_station to stations.name
        $idMap = DB::table('nec_polling_stations')
            ->pluck('id', 'name')
            ->toArray();

        DB::table('nec_voters')
            ->whereNull('polling_station_id')
            ->whereNotNull('polling_station')
            ->orderBy('id')
            ->each(function ($voter) use ($idMap) {
                $match = $idMap[$voter->polling_station] ?? null;
                if ($match) {
                    DB::table('nec_voters')->where('id', $voter->id)->update(['polling_station_id' => $match]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('nec_voters', function (Blueprint $table) {
            if (Schema::hasColumn('nec_voters', 'polling_station_id')) {
                Schema::table('nec_voters', function (Blueprint $t2) {
                    $t2->dropIndex(['polling_station_id']);
                    $t2->dropColumn('polling_station_id');
                });
            }
        });

        // Revert stations to a placeholder code (don't null them — seed can override)
        DB::table('nec_polling_stations')
            ->where('code', 'like', '%-PS-%')
            ->update(['code' => null]);
    }
};
