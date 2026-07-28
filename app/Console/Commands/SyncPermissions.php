<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Models\Permission;

class SyncPermissions extends Command
{
    protected $signature = 'permissions:sync';
    protected $description = 'Scan all admin routes and sync permissions to the database';

    public function handle(): int
    {
        $now = now();
        $existing = DB::table('nec_permissions')->pluck('slug')->flip()->toArray();
        $created = 0;
        $modules = [];

        foreach (Route::getRoutes() as $route) {
            $name = $route->getName();
            if (!$name || !str_starts_with($name, 'admin.')) continue;

            $parts = explode('.', $name);
            if (count($parts) < 2) continue;

            $module = $parts[1];
            $action = $parts[2] ?? 'index';
            $modules[$module] = true;

            if ($action === 'index') $slug = "{$module}.view";
            elseif ($action === 'create' || $action === 'store') $slug = "{$module}.create";
            elseif (in_array($action, ['edit', 'update'])) $slug = "{$module}.update";
            elseif (in_array($action, ['destroy', 'force-delete'])) $slug = "{$module}.delete";
            elseif ($action === 'restore') $slug = "{$module}.restore";
            elseif ($action === 'export') $slug = "{$module}.export";
            else $slug = "{$module}.{$action}";

            if (!isset($existing[$slug])) {
                $moduleName = ucfirst(str_replace('-', ' ', $module));
                $actionName = ucfirst($action);
                DB::table('nec_permissions')->updateOrInsert(
                    ['slug' => $slug],
                    [
                        'name' => "{$actionName} {$moduleName}",
                        'module' => $module,
                        'description' => "Auto-discovered from route: {$name}",
                        'is_active' => true,
                        'created_at' => $now,
                    ]
                );
                $existing[$slug] = true;
                $created++;
                $this->line("  <info>+</info> Created: {$slug}");
            }
        }

        Permission::flushCache();

        $this->newLine();
        $this->info("Sync complete. {$created} new permissions created.");
        $this->info("Total permissions: " . DB::table('nec_permissions')->count());
        $this->info("Modules found: " . implode(', ', array_keys($modules)));

        return Command::SUCCESS;
    }
}
