<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('nec_gallery')
            ->where('image_path', 'LIKE', 'nec-mirror/%')
            ->update(['image_path' => DB::raw("CONCAT('storage/', image_path)")]);
    }

    public function down(): void
    {
        DB::table('nec_gallery')
            ->where('image_path', 'LIKE', 'storage/nec-mirror/%')
            ->update(['image_path' => DB::raw("REPLACE(image_path, 'storage/', '')")]);
    }
};