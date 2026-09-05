<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $stations = DB::table('nec_polling_stations')->whereNull('code')->orderBy('id')->get();

        foreach ($stations as $station) {
            do {
                $code = 'PS' . strtoupper(substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 6));
            } while (DB::table('nec_polling_stations')->where('code', $code)->exists());

            DB::table('nec_polling_stations')->where('id', $station->id)->update(['code' => $code]);
        }
    }

    public function down(): void
    {
        DB::table('nec_polling_stations')->update(['code' => null]);
    }
};