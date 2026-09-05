<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $permissions = [
            // Dashboard
            ['slug' => 'dashboard.view', 'name' => 'View Dashboard', 'module' => 'dashboard'],

            // Voters
            ['slug' => 'voters.view', 'name' => 'View Voters', 'module' => 'voters'],
            ['slug' => 'voters.create', 'name' => 'Create Voters', 'module' => 'voters'],
            ['slug' => 'voters.update', 'name' => 'Update Voters', 'module' => 'voters'],
            ['slug' => 'voters.delete', 'name' => 'Delete Voters', 'module' => 'voters'],
            ['slug' => 'voters.export', 'name' => 'Export Voters', 'module' => 'voters'],
            ['slug' => 'voters.restore', 'name' => 'Restore Voters', 'module' => 'voters'],

            // Voter Transfers
            ['slug' => 'voter-transfers.view', 'name' => 'View Voter Transfers', 'module' => 'voter-transfers'],
            ['slug' => 'voter-transfers.approve', 'name' => 'Approve Voter Transfers', 'module' => 'voter-transfers'],
            ['slug' => 'voter-transfers.reject', 'name' => 'Reject Voter Transfers', 'module' => 'voter-transfers'],

            // Users
            ['slug' => 'users.view', 'name' => 'View Users', 'module' => 'users'],
            ['slug' => 'users.create', 'name' => 'Create Users', 'module' => 'users'],
            ['slug' => 'users.update', 'name' => 'Update Users', 'module' => 'users'],
            ['slug' => 'users.delete', 'name' => 'Delete Users', 'module' => 'users'],
            ['slug' => 'users.restore', 'name' => 'Restore Users', 'module' => 'users'],
            ['slug' => 'users.reset-password', 'name' => 'Reset User Passwords', 'module' => 'users'],

            // Staff
            ['slug' => 'staff.view', 'name' => 'View Staff', 'module' => 'staff'],
            ['slug' => 'staff.create', 'name' => 'Create Staff', 'module' => 'staff'],
            ['slug' => 'staff.update', 'name' => 'Update Staff', 'module' => 'staff'],
            ['slug' => 'staff.delete', 'name' => 'Delete Staff', 'module' => 'staff'],
            ['slug' => 'staff.assign', 'name' => 'Assign Staff Roles', 'module' => 'staff'],

            // Political Parties
            ['slug' => 'parties.view', 'name' => 'View Parties', 'module' => 'parties'],
            ['slug' => 'parties.create', 'name' => 'Create Parties', 'module' => 'parties'],
            ['slug' => 'parties.update', 'name' => 'Update Parties', 'module' => 'parties'],
            ['slug' => 'parties.delete', 'name' => 'Delete Parties', 'module' => 'parties'],

            // Constituencies
            ['slug' => 'constituencies.view', 'name' => 'View Constituencies', 'module' => 'constituencies'],
            ['slug' => 'constituencies.create', 'name' => 'Create Constituencies', 'module' => 'constituencies'],
            ['slug' => 'constituencies.update', 'name' => 'Update Constituencies', 'module' => 'constituencies'],
            ['slug' => 'constituencies.delete', 'name' => 'Delete Constituencies', 'module' => 'constituencies'],

            // Candidates
            ['slug' => 'candidates.view', 'name' => 'View Candidates', 'module' => 'candidates'],
            ['slug' => 'candidates.create', 'name' => 'Create Candidates', 'module' => 'candidates'],
            ['slug' => 'candidates.update', 'name' => 'Update Candidates', 'module' => 'candidates'],
            ['slug' => 'candidates.delete', 'name' => 'Delete Candidates', 'module' => 'candidates'],

            // Results
            ['slug' => 'results.view', 'name' => 'View Results', 'module' => 'results'],
            ['slug' => 'results.create', 'name' => 'Create Results', 'module' => 'results'],
            ['slug' => 'results.update', 'name' => 'Update Results', 'module' => 'results'],
            ['slug' => 'results.delete', 'name' => 'Delete Results', 'module' => 'results'],

            // Observers
            ['slug' => 'observers.view', 'name' => 'View Observers', 'module' => 'observers'],
            ['slug' => 'observers.review', 'name' => 'Review Observers', 'module' => 'observers'],

            // Commissioners
            ['slug' => 'commissioners.view', 'name' => 'View Commissioners', 'module' => 'commissioners'],
            ['slug' => 'commissioners.create', 'name' => 'Create Commissioners', 'module' => 'commissioners'],
            ['slug' => 'commissioners.update', 'name' => 'Update Commissioners', 'module' => 'commissioners'],
            ['slug' => 'commissioners.delete', 'name' => 'Delete Commissioners', 'module' => 'commissioners'],

            // Polling Stations
            ['slug' => 'polling-stations.view', 'name' => 'View Polling Stations', 'module' => 'polling-stations'],
            ['slug' => 'polling-stations.create', 'name' => 'Create Polling Stations', 'module' => 'polling-stations'],
            ['slug' => 'polling-stations.update', 'name' => 'Update Polling Stations', 'module' => 'polling-stations'],
            ['slug' => 'polling-stations.delete', 'name' => 'Delete Polling Stations', 'module' => 'polling-stations'],

            // News
            ['slug' => 'news.view', 'name' => 'View News', 'module' => 'news'],
            ['slug' => 'news.create', 'name' => 'Create News', 'module' => 'news'],
            ['slug' => 'news.update', 'name' => 'Update News', 'module' => 'news'],
            ['slug' => 'news.delete', 'name' => 'Delete News', 'module' => 'news'],

            // Announcements
            ['slug' => 'announcements.view', 'name' => 'View Announcements', 'module' => 'announcements'],
            ['slug' => 'announcements.create', 'name' => 'Create Announcements', 'module' => 'announcements'],
            ['slug' => 'announcements.update', 'name' => 'Update Announcements', 'module' => 'announcements'],
            ['slug' => 'announcements.delete', 'name' => 'Delete Announcements', 'module' => 'announcements'],

            // Gallery
            ['slug' => 'gallery.view', 'name' => 'View Gallery', 'module' => 'gallery'],
            ['slug' => 'gallery.create', 'name' => 'Create Gallery Items', 'module' => 'gallery'],
            ['slug' => 'gallery.update', 'name' => 'Update Gallery Items', 'module' => 'gallery'],
            ['slug' => 'gallery.delete', 'name' => 'Delete Gallery Items', 'module' => 'gallery'],

            // Speeches
            ['slug' => 'speeches.view', 'name' => 'View Speeches', 'module' => 'speeches'],
            ['slug' => 'speeches.create', 'name' => 'Create Speeches', 'module' => 'speeches'],
            ['slug' => 'speeches.update', 'name' => 'Update Speeches', 'module' => 'speeches'],
            ['slug' => 'speeches.delete', 'name' => 'Delete Speeches', 'module' => 'speeches'],

            // Videos
            ['slug' => 'videos.view', 'name' => 'View Videos', 'module' => 'videos'],
            ['slug' => 'videos.create', 'name' => 'Create Videos', 'module' => 'videos'],
            ['slug' => 'videos.update', 'name' => 'Update Videos', 'module' => 'videos'],
            ['slug' => 'videos.delete', 'name' => 'Delete Videos', 'module' => 'videos'],

            // FAQs
            ['slug' => 'faqs.view', 'name' => 'View FAQs', 'module' => 'faqs'],
            ['slug' => 'faqs.create', 'name' => 'Create FAQs', 'module' => 'faqs'],
            ['slug' => 'faqs.update', 'name' => 'Update FAQs', 'module' => 'faqs'],
            ['slug' => 'faqs.delete', 'name' => 'Delete FAQs', 'module' => 'faqs'],
            ['slug' => 'faqs.reorder', 'name' => 'Reorder FAQs', 'module' => 'faqs'],

            // Education
            ['slug' => 'education.view', 'name' => 'View Education Materials', 'module' => 'education'],
            ['slug' => 'education.create', 'name' => 'Create Education Materials', 'module' => 'education'],
            ['slug' => 'education.update', 'name' => 'Update Education Materials', 'module' => 'education'],
            ['slug' => 'education.delete', 'name' => 'Delete Education Materials', 'module' => 'education'],

            // Subscribers
            ['slug' => 'subscribers.view', 'name' => 'View Subscribers', 'module' => 'subscribers'],
            ['slug' => 'subscribers.export', 'name' => 'Export Subscribers', 'module' => 'subscribers'],

            // Contacts
            ['slug' => 'contacts.view', 'name' => 'View Contact Messages', 'module' => 'contacts'],
            ['slug' => 'contacts.reply', 'name' => 'Reply to Contacts', 'module' => 'contacts'],
            ['slug' => 'contacts.delete', 'name' => 'Delete Contact Messages', 'module' => 'contacts'],

            // Activity Logs
            ['slug' => 'activity-logs.view', 'name' => 'View Activity Logs', 'module' => 'activity-logs'],
            ['slug' => 'activity-logs.export', 'name' => 'Export Activity Logs', 'module' => 'activity-logs'],
            ['slug' => 'activity-logs.clear', 'name' => 'Clear Activity Logs', 'module' => 'activity-logs'],

            // Settings
            ['slug' => 'settings.view', 'name' => 'View Settings', 'module' => 'settings'],
            ['slug' => 'settings.update', 'name' => 'Update Settings', 'module' => 'settings'],

            // Permissions (meta)
            ['slug' => 'permissions.view', 'name' => 'View Permissions', 'module' => 'permissions'],
            ['slug' => 'permissions.manage', 'name' => 'Manage Permissions', 'module' => 'permissions'],
        ];

        foreach ($permissions as $p) {
            DB::table('nec_permissions')->updateOrInsert(
                ['slug' => $p['slug']],
                array_merge($p, ['created_at' => $now])
            );
        }

        $roleDefaults = [
            'super_admin' => 'all',
            'admin' => [
                'dashboard.view',
                'voters.view', 'voters.create', 'voters.update', 'voters.export',
                'voter-transfers.view', 'voter-transfers.approve', 'voter-transfers.reject', 'voter-transfers.export',
                'users.view', 'users.create', 'users.update',
                'staff.view', 'staff.create', 'staff.update',
                'parties.view', 'parties.create', 'parties.update',
                'constituencies.view', 'constituencies.create', 'constituencies.update',
                'candidates.view', 'candidates.create', 'candidates.update',
                'results.view', 'results.create', 'results.update',
                'observers.view', 'observers.review',
                'commissioners.view', 'commissioners.create', 'commissioners.update',
                'polling-stations.view', 'polling-stations.create', 'polling-stations.update',
                'news.view', 'news.create', 'news.update',
                'announcements.view', 'announcements.create', 'announcements.update',
                'gallery.view', 'gallery.create', 'gallery.update',
                'speeches.view', 'speeches.create', 'speeches.update',
                'videos.view', 'videos.create', 'videos.update',
                'faqs.view', 'faqs.create', 'faqs.update', 'faqs.reorder',
                'education.view', 'education.create', 'education.update',
                'subscribers.view', 'subscribers.export',
                'contacts.view', 'contacts.reply',
                'activity-logs.view', 'activity-logs.export',
                'settings.view', 'settings.update',
            ],
            'state_coordinator' => [
                'dashboard.view',
                'voters.view', 'voters.export',
                'constituencies.view',
                'polling-stations.view',
                'observers.view',
                'contacts.view', 'contacts.reply',
                'activity-logs.view',
            ],
            'constituency_officer' => [
                'dashboard.view',
                'voters.view', 'voters.create', 'voters.update',
                'polling-stations.view',
                'observers.view',
            ],
            'registration_officer' => [
                'dashboard.view',
                'voters.view', 'voters.create', 'voters.update',
            ],
            'polling_officer' => [
                'dashboard.view',
                'voters.view',
                'results.view',
            ],
            'data_entry' => [
                'dashboard.view',
                'voters.view', 'voters.create', 'voters.update',
                'news.view', 'news.create',
                'announcements.view', 'announcements.create',
            ],
            'content_editor' => [
                'dashboard.view',
                'news.view', 'news.create', 'news.update', 'news.delete',
                'announcements.view', 'announcements.create', 'announcements.update', 'announcements.delete',
                'gallery.view', 'gallery.create', 'gallery.update', 'gallery.delete',
                'speeches.view', 'speeches.create', 'speeches.update', 'speeches.delete',
                'videos.view', 'videos.create', 'videos.update', 'videos.delete',
                'faqs.view', 'faqs.create', 'faqs.update', 'faqs.delete', 'faqs.reorder',
                'education.view', 'education.create', 'education.update', 'education.delete',
            ],
            'viewer' => [
                'dashboard.view',
                'voters.view',
                'parties.view',
                'constituencies.view',
                'candidates.view',
                'results.view',
                'observers.view',
                'commissioners.view',
                'news.view',
                'announcements.view',
                'gallery.view',
                'speeches.view',
                'faqs.view',
            ],
        ];

        $allSlugs = DB::table('nec_permissions')->pluck('id', 'slug')->toArray();

        foreach ($roleDefaults as $role => $allowed) {
            if ($allowed === 'all') {
                $permIds = array_values($allSlugs);
            } else {
                $permIds = array_map(fn ($slug) => $allSlugs[$slug] ?? null, $allowed);
                $permIds = array_filter($permIds);
            }

            foreach ($permIds as $permId) {
                DB::table('nec_role_permissions')->updateOrInsert(
                    ['role' => $role, 'permission_id' => $permId],
                    ['created_at' => $now]
                );
            }
        }

        $this->command->info('Permissions: ' . DB::table('nec_permissions')->count());
        $this->command->info('Role-Permission assignments: ' . DB::table('nec_role_permissions')->count());
    }
}
