<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE nec_political_parties ADD COLUMN status_new TINYINT DEFAULT 1 NULL AFTER status');
        DB::statement('UPDATE nec_political_parties SET status_new = CASE status WHEN "active" THEN 1 WHEN "inactive" THEN 0 ELSE NULL END');
        DB::statement('ALTER TABLE nec_political_parties DROP COLUMN status');
        DB::statement('ALTER TABLE nec_political_parties RENAME COLUMN status_new TO status');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE nec_political_parties ADD COLUMN status_old ENUM("active","inactive","trash") DEFAULT "active" NULL AFTER status');
        DB::statement('UPDATE nec_political_parties SET status_old = CASE WHEN status = 1 THEN "active" WHEN status = 0 THEN "inactive" ELSE "trash" END');
        DB::statement('ALTER TABLE nec_political_parties DROP COLUMN status');
        DB::statement('ALTER TABLE nec_political_parties RENAME COLUMN status_old TO status');
    }
};
