<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        $columns = ['national_id', 'phone', 'passport_number', 'email'];

        foreach ($columns as $col) {
            $index = "nec_voters_{$col}_unique";
            $existing = collect(Schema::getColumnListing('nec_voters'));
            // drop the redundant non-unique index if present before adding the unique one
            try {
                if (Schema::hasIndex('nec_voters', "nec_voters_{$col}_index")) {
                    Schema::table('nec_voters', function (Blueprint $table) use ($col) {
                        $table->dropIndex("nec_voters_{$col}_index");
                    });
                }
            } catch (\Throwable $e) {
                // index may not exist; continue
            }

            Schema::table('nec_voters', function (Blueprint $table) use ($col, $index) {
                $table->unique($col, $index);
            });
        }
    }

    public function down()
    {
        foreach (['national_id', 'phone', 'passport_number', 'email'] as $col) {
            Schema::table('nec_voters', function (Blueprint $table) use ($col) {
                $table->dropUnique("nec_voters_{$col}_unique");
            });
        }
    }
};