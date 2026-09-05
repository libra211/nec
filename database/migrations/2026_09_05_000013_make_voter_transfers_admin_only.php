<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $ids = DB::table('nec_permissions')->where('module', 'voter-transfers')->pluck('id');

        if ($ids->isNotEmpty()) {
            DB::table('nec_role_permissions')
                ->whereIn('permission_id', $ids)
                ->whereNotIn('role', ['super_admin', 'admin'])
                ->delete();
        }

        $exportId = DB::table('nec_permissions')->where('slug', 'voter-transfers.export')->value('id');
        if ($exportId) {
            DB::table('nec_role_permissions')->updateOrInsert(
                ['role' => 'admin', 'permission_id' => $exportId],
                ['created_at' => now()]
            );
        }

        foreach (['admin', 'state_coordinator', 'constituency_officer'] as $role) {
            cache()->forget("permissions_{$role}");
        }
    }

    public function down(): void
    {
        DB::table('nec_role_permissions')
            ->whereNotIn('role', ['super_admin', 'admin'])
            ->whereIn('permission_id', DB::table('nec_permissions')->where('module', 'voter-transfers')->pluck('id'))
            ->delete();

        foreach (['admin', 'state_coordinator', 'constituency_officer'] as $role) {
            cache()->forget("permissions_{$role}");
        }
    }
};