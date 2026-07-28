<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSystemSeeder extends Seeder
{
    public function run(): void
    {
        $states = [
            'Central Equatoria', 'Eastern Equatoria', 'Western Equatoria',
            'Jonglei', 'Unity', 'Upper Nile', 'Lakes',
            'Northern Bahr el Ghazal', 'Western Bahr el Ghazal', 'Warrap',
        ];

        $now = now();
        $password = Hash::make('password');

        // Update existing admin user
        DB::table('nec_users')->where('email', 'admin@nec.gov.ss')->update([
            'role' => 'super_admin',
            'department' => 'Administration',
            'state' => 'Central Equatoria',
            'position' => 'System Administrator',
            'employee_id' => 'NEC-001',
            'phone' => '+211912000001',
        ]);

        // Super Admins (2)
        $users = [
            ['email' => 'chairperson@nec.gov.ss', 'phone' => '+211912000010', 'name' => 'Dr. Abel Alier Kuai', 'password' => $password, 'role' => 'super_admin', 'department' => 'Executive Office', 'state' => 'Central Equatoria', 'position' => 'Chairperson', 'employee_id' => 'NEC-010', 'status' => 'active', 'created_at' => $now],
            ['email' => 'secretary@nec.gov.ss', 'phone' => '+211912000011', 'name' => 'Prof. James Obong Abyei', 'password' => $password, 'role' => 'super_admin', 'department' => 'Executive Office', 'state' => 'Central Equatoria', 'position' => 'Secretary General', 'employee_id' => 'NEC-011', 'status' => 'active', 'created_at' => $now],

            // State Coordinators (10)
            ['email' => 'coord.ce@nec.gov.ss', 'phone' => '+211912000020', 'name' => 'James Laku Lado', 'password' => $password, 'role' => 'state_coordinator', 'department' => 'Election Operations', 'state' => 'Central Equatoria', 'position' => 'State Coordinator', 'employee_id' => 'NEC-020', 'status' => 'active', 'created_at' => $now],
            ['email' => 'coord.ee@nec.gov.ss', 'phone' => '+211912000021', 'name' => 'Sarah Nimirio Lokai', 'password' => $password, 'role' => 'state_coordinator', 'department' => 'Election Operations', 'state' => 'Eastern Equatoria', 'position' => 'State Coordinator', 'employee_id' => 'NEC-021', 'status' => 'active', 'created_at' => $now],
            ['email' => 'coord.we@nec.gov.ss', 'phone' => '+211912000022', 'name' => 'Paul Taban Mijak', 'password' => $password, 'role' => 'state_coordinator', 'department' => 'Election Operations', 'state' => 'Western Equatoria', 'position' => 'State Coordinator', 'employee_id' => 'NEC-022', 'status' => 'active', 'created_at' => $now],
            ['email' => 'coord.jong@nec.gov.ss', 'phone' => '+211912000023', 'name' => 'Deng Kuol Arop', 'password' => $password, 'role' => 'state_coordinator', 'department' => 'Election Operations', 'state' => 'Jonglei', 'position' => 'State Coordinator', 'employee_id' => 'NEC-023', 'status' => 'active', 'created_at' => $now],
            ['email' => 'coord.unity@nec.gov.ss', 'phone' => '+211912000024', 'name' => 'Nyaluak Lual Kiir', 'password' => $password, 'role' => 'state_coordinator', 'department' => 'Election Operations', 'state' => 'Unity', 'position' => 'State Coordinator', 'employee_id' => 'NEC-024', 'status' => 'active', 'created_at' => $now],
            ['email' => 'coord.upn@nec.gov.ss', 'phone' => '+211912000025', 'name' => 'Peter Gatbel Kuol', 'password' => $password, 'role' => 'state_coordinator', 'department' => 'Election Operations', 'state' => 'Upper Nile', 'position' => 'State Coordinator', 'employee_id' => 'NEC-025', 'status' => 'active', 'created_at' => $now],
            ['email' => 'coord.lakes@nec.gov.ss', 'phone' => '+211912000026', 'name' => 'Achiek Deng Mone', 'password' => $password, 'role' => 'state_coordinator', 'department' => 'Election Operations', 'state' => 'Lakes', 'position' => 'State Coordinator', 'employee_id' => 'NEC-026', 'status' => 'active', 'created_at' => $now],
            ['email' => 'coord.nbg@nec.gov.ss', 'phone' => '+211912000027', 'name' => 'Mary Akoi Ajack', 'password' => $password, 'role' => 'state_coordinator', 'department' => 'Election Operations', 'state' => 'Northern Bahr el Ghazal', 'position' => 'State Coordinator', 'employee_id' => 'NEC-027', 'status' => 'active', 'created_at' => $now],
            ['email' => 'coord.wbg@nec.gov.ss', 'phone' => '+211912000028', 'name' => 'Stephen Par Kuol', 'password' => $password, 'role' => 'state_coordinator', 'department' => 'Election Operations', 'state' => 'Western Bahr el Ghazal', 'position' => 'State Coordinator', 'employee_id' => 'NEC-028', 'status' => 'active', 'created_at' => $now],
            ['email' => 'coord.warrap@nec.gov.ss', 'phone' => '+211912000029', 'name' => 'Kuol Athian Mou', 'password' => $password, 'role' => 'state_coordinator', 'department' => 'Election Operations', 'state' => 'Warrap', 'position' => 'State Coordinator', 'employee_id' => 'NEC-029', 'status' => 'active', 'created_at' => $now],

            // Constituency Officers (5)
            ['email' => 'const.juba1@nec.gov.ss', 'phone' => '+211912000030', 'name' => 'Michael Majak Akol', 'password' => $password, 'role' => 'constituency_officer', 'department' => 'Constituency Management', 'state' => 'Central Equatoria', 'position' => 'Constituency Officer - Juba', 'employee_id' => 'NEC-030', 'status' => 'active', 'created_at' => $now],
            ['email' => 'const.bor@nec.gov.ss', 'phone' => '+211912000031', 'name' => 'Nyabol Khor Puok', 'password' => $password, 'role' => 'constituency_officer', 'department' => 'Constituency Management', 'state' => 'Jonglei', 'position' => 'Constituency Officer - Bor', 'employee_id' => 'NEC-031', 'status' => 'active', 'created_at' => $now],
            ['email' => 'const.wau@nec.gov.ss', 'phone' => '+211912000032', 'name' => 'Simon Akuek Bol', 'password' => $password, 'role' => 'constituency_officer', 'department' => 'Constituency Management', 'state' => 'Western Bahr el Ghazal', 'position' => 'Constituency Officer - Wau', 'employee_id' => 'NEC-032', 'status' => 'active', 'created_at' => $now],
            ['email' => 'const.malakal@nec.gov.ss', 'phone' => '+211912000033', 'name' => 'Elizabeth Nyabuoy Galuak', 'password' => $password, 'role' => 'constituency_officer', 'department' => 'Constituency Management', 'state' => 'Upper Nile', 'position' => 'Constituency Officer - Malakal', 'employee_id' => 'NEC-033', 'status' => 'active', 'created_at' => $now],
            ['email' => 'const.yambio@nec.gov.ss', 'phone' => '+211912000034', 'name' => 'Andrea Sabastiano Ture', 'password' => $password, 'role' => 'constituency_officer', 'department' => 'Constituency Management', 'state' => 'Western Equatoria', 'position' => 'Constituency Officer - Yambio', 'employee_id' => 'NEC-034', 'status' => 'inactive', 'created_at' => $now],

            // Registration Officers (5)
            ['email' => 'reg.ce1@nec.gov.ss', 'phone' => '+211912000040', 'name' => 'Joseph Okech Inyu', 'password' => $password, 'role' => 'registration_officer', 'department' => 'Voter Registration', 'state' => 'Central Equatoria', 'position' => 'Registration Officer', 'employee_id' => 'NEC-040', 'status' => 'active', 'created_at' => $now],
            ['email' => 'reg.ee1@nec.gov.ss', 'phone' => '+211912000041', 'name' => 'Agnes Lopidia Ekiru', 'password' => $password, 'role' => 'registration_officer', 'department' => 'Voter Registration', 'state' => 'Eastern Equatoria', 'position' => 'Registration Officer', 'employee_id' => 'NEC-041', 'status' => 'active', 'created_at' => $now],
            ['email' => 'reg.jon1@nec.gov.ss', 'phone' => '+211912000042', 'name' => 'David Gai Nyuot', 'password' => $password, 'role' => 'registration_officer', 'department' => 'Voter Registration', 'state' => 'Jonglei', 'position' => 'Registration Officer', 'employee_id' => 'NEC-042', 'status' => 'active', 'created_at' => $now],
            ['email' => 'reg.uni1@nec.gov.ss', 'phone' => '+211912000043', 'name' => 'Grace Nyabuoy Gatluak', 'password' => $password, 'role' => 'registration_officer', 'department' => 'Voter Registration', 'state' => 'Unity', 'position' => 'Registration Officer', 'employee_id' => 'NEC-043', 'status' => 'active', 'created_at' => $now],
            ['email' => 'reg.lak1@nec.gov.ss', 'phone' => '+211912000044', 'name' => 'Samuel Maker Kuol', 'password' => $password, 'role' => 'registration_officer', 'department' => 'Voter Registration', 'state' => 'Lakes', 'position' => 'Registration Officer', 'employee_id' => 'NEC-044', 'status' => 'active', 'created_at' => $now],

            // Polling Officers (3)
            ['email' => 'poll.ce1@nec.gov.ss', 'phone' => '+211912000050', 'name' => 'Charles Wani Stephen', 'password' => $password, 'role' => 'polling_officer', 'department' => 'Polling Operations', 'state' => 'Central Equatoria', 'position' => 'Presiding Officer', 'employee_id' => 'NEC-050', 'status' => 'active', 'created_at' => $now],
            ['email' => 'poll.ee1@nec.gov.ss', 'phone' => '+211912000051', 'name' => 'Florence Anidzo Muzamil', 'password' => $password, 'role' => 'polling_officer', 'department' => 'Polling Operations', 'state' => 'Eastern Equatoria', 'position' => 'Presiding Officer', 'employee_id' => 'NEC-051', 'status' => 'active', 'created_at' => $now],
            ['email' => 'poll.upn1@nec.gov.ss', 'phone' => '+211912000052', 'name' => 'Ibrahim Hassan Abdalla', 'password' => $password, 'role' => 'polling_officer', 'department' => 'Polling Operations', 'state' => 'Upper Nile', 'position' => 'Presiding Officer', 'employee_id' => 'NEC-052', 'status' => 'active', 'created_at' => $now],

            // Data Entry (2)
            ['email' => 'data1@nec.gov.ss', 'phone' => '+211912000060', 'name' => 'Amina Abuk Deng', 'password' => $password, 'role' => 'data_entry', 'department' => 'Data Management', 'state' => 'Central Equatoria', 'position' => 'Data Entry Clerk', 'employee_id' => 'NEC-060', 'status' => 'active', 'created_at' => $now],
            ['email' => 'data2@nec.gov.ss', 'phone' => '+211912000061', 'name' => 'Bol Stephen Keah', 'password' => $password, 'role' => 'data_entry', 'department' => 'Data Management', 'state' => 'Central Equatoria', 'position' => 'Data Entry Clerk', 'employee_id' => 'NEC-061', 'status' => 'active', 'created_at' => $now],

            // Content Editors (2)
            ['email' => 'editor1@nec.gov.ss', 'phone' => '+211912000070', 'name' => 'Ruth Nundu Muse', 'password' => $password, 'role' => 'content_editor', 'department' => 'Communications', 'state' => 'Central Equatoria', 'position' => 'Senior Editor', 'employee_id' => 'NEC-070', 'status' => 'active', 'created_at' => $now],
            ['email' => 'editor2@nec.gov.ss', 'phone' => '+211912000071', 'name' => 'Emmanuel Keni Wani', 'password' => $password, 'role' => 'content_editor', 'department' => 'Communications', 'state' => 'Central Equatoria', 'position' => 'Content Editor', 'employee_id' => 'NEC-071', 'status' => 'active', 'created_at' => $now],

            // System Admin (1)
            ['email' => 'itadmin@nec.gov.ss', 'phone' => '+211912000080', 'name' => 'Dr. Peter Loku Duku', 'password' => $password, 'role' => 'admin', 'department' => 'Information Technology', 'state' => 'Central Equatoria', 'position' => 'Chief Information Officer', 'employee_id' => 'NEC-080', 'status' => 'active', 'created_at' => $now],

            // Viewers (2)
            ['email' => 'viewer1@nec.gov.ss', 'phone' => '+211912000090', 'name' => 'Observer One', 'password' => $password, 'role' => 'viewer', 'department' => 'Observation', 'state' => 'Central Equatoria', 'position' => 'External Observer', 'employee_id' => 'NEC-090', 'status' => 'active', 'created_at' => $now],
            ['email' => 'viewer2@nec.gov.ss', 'phone' => '+211912000091', 'name' => 'International Monitor', 'password' => $password, 'role' => 'viewer', 'department' => 'Observation', 'state' => 'Central Equatoria', 'position' => 'International Observer', 'employee_id' => 'NEC-091', 'status' => 'active', 'created_at' => $now],
        ];

        foreach ($users as $user) {
            DB::table('nec_users')->updateOrInsert(
                ['email' => $user['email']],
                array_merge($user, ['last_active' => $now])
            );
        }

        // B. Voter Portal Accounts (20)
        $voters = DB::table('nec_voters')->orderBy('id')->limit(20)->get();
        foreach ($voters as $voter) {
            $email = 'voter' . $voter->id . '@voter.nec.gov.ss';

            DB::table('nec_voter_accounts')->updateOrInsert(
                ['voter_id' => $voter->id],
                [
                    'email' => $email,
                    'password' => $password,
                    'pin_code' => str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT),
                    'email_verified_at' => $now,
                    'status' => 'active',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }

        // C. Voter Transfers (15)
        $transfers = [
            ['voter_id' => 1, 'voter_identifier' => '1000001', 'full_name' => 'Test Voter One', 'from_constituency' => 'Juba Central', 'to_constituency' => 'Juba North', 'from_state' => 'Central Equatoria', 'to_state' => 'Central Equatoria', 'reason' => 'Relocated due to employment transfer', 'status' => 'approved', 'reviewed_at' => $now, 'admin_notes' => 'Approved - verified relocation documents', 'created_at' => now()->subDays(20)],
            ['voter_id' => 2, 'voter_identifier' => '1000002', 'full_name' => 'Test Voter Two', 'from_constituency' => 'Bor South', 'to_constituency' => 'Bor North', 'from_state' => 'Jonglei', 'to_state' => 'Jonglei', 'reason' => 'Family relocation', 'status' => 'pending', 'created_at' => now()->subDays(5)],
            ['voter_id' => 3, 'voter_identifier' => '1000003', 'full_name' => 'Test Voter Three', 'from_constituency' => 'Torit Town', 'to_constituency' => 'Napak', 'from_state' => 'Eastern Equatoria', 'to_state' => 'Eastern Equatoria', 'reason' => 'Marriage relocation', 'status' => 'pending', 'created_at' => now()->subDays(3)],
            ['voter_id' => 4, 'voter_identifier' => '1000004', 'full_name' => 'Test Voter Four', 'from_constituency' => 'Wau Central', 'to_constituency' => 'Raga', 'from_state' => 'Western Bahr el Ghazal', 'to_state' => 'Western Bahr el Ghazal', 'reason' => 'Agricultural resettlement', 'status' => 'approved', 'reviewed_at' => $now, 'admin_notes' => 'Approved', 'created_at' => now()->subDays(15)],
            ['voter_id' => 5, 'voter_identifier' => '1000005', 'full_name' => 'Test Voter Five', 'from_constituency' => 'Malakal Town', 'to_constituency' => 'Renk', 'from_state' => 'Upper Nile', 'to_state' => 'Upper Nile', 'reason' => 'Return to home area', 'status' => 'rejected', 'admin_notes' => 'Insufficient documentation provided', 'reviewed_at' => $now, 'created_at' => now()->subDays(25)],
            ['voter_id' => 6, 'voter_identifier' => '1000006', 'full_name' => 'Test Voter Six', 'from_constituency' => 'Yambio Town', 'to_constituency' => 'Nzara', 'from_state' => 'Western Equatoria', 'to_state' => 'Western Equatoria', 'reason' => 'Family reconnection', 'status' => 'pending', 'created_at' => now()->subDays(2)],
            ['voter_id' => 7, 'voter_identifier' => '1000007', 'full_name' => 'Test Voter Seven', 'from_constituency' => 'Rumbek Centre', 'to_constituency' => 'Wulu', 'from_state' => 'Lakes', 'to_state' => 'Lakes', 'reason' => 'Relocation after marriage', 'status' => 'approved', 'reviewed_at' => $now, 'admin_notes' => 'Approved', 'created_at' => now()->subDays(18)],
            ['voter_id' => 8, 'voter_identifier' => '1000008', 'full_name' => 'Test Voter Eight', 'from_constituency' => 'Aweil Centre', 'to_constituency' => 'Aweil East', 'from_state' => 'Northern Bahr el Ghazal', 'to_state' => 'Northern Bahr el Ghazal', 'reason' => 'Employment transfer', 'status' => 'pending', 'created_at' => now()->subDays(4)],
            ['voter_id' => 9, 'voter_identifier' => '1000009', 'full_name' => 'Test Voter Nine', 'from_constituency' => 'Bentiu Town', 'to_constituency' => 'Mayom', 'from_state' => 'Unity', 'to_state' => 'Unity', 'reason' => 'Return to ancestral home', 'status' => 'approved', 'reviewed_at' => $now, 'admin_notes' => 'Approved', 'created_at' => now()->subDays(22)],
            ['voter_id' => 10, 'voter_identifier' => '1000010', 'full_name' => 'Test Voter Ten', 'from_constituency' => 'Warrap Town', 'to_constituency' => 'Kuajok', 'from_state' => 'Warrap', 'to_state' => 'Warrap', 'reason' => 'Proximity to workplace', 'status' => 'rejected', 'admin_notes' => 'Incomplete application form', 'reviewed_at' => $now, 'created_at' => now()->subDays(28)],
            ['voter_id' => 11, 'voter_identifier' => '1000011', 'full_name' => 'Test Voter Eleven', 'from_constituency' => 'Juba Central', 'to_constituency' => 'Kajo-Keji', 'from_state' => 'Central Equatoria', 'to_state' => 'Central Equatoria', 'reason' => 'Family settlement', 'status' => 'pending', 'created_at' => now()->subDays(1)],
            ['voter_id' => 12, 'voter_identifier' => '1000012', 'full_name' => 'Test Voter Twelve', 'from_constituency' => 'Torit Town', 'to_constituency' => 'Kapoeta', 'from_state' => 'Eastern Equatoria', 'to_state' => 'Eastern Equatoria', 'reason' => 'Community resettlement', 'status' => 'pending', 'created_at' => now()->subDays(7)],
            ['voter_id' => 13, 'voter_identifier' => '1000013', 'full_name' => 'Test Voter Thirteen', 'from_constituency' => 'Bor South', 'to_constituency' => 'Akobo', 'from_state' => 'Jonglei', 'to_state' => 'Jonglei', 'reason' => 'Return to origin', 'status' => 'approved', 'reviewed_at' => $now, 'admin_notes' => 'Approved', 'created_at' => now()->subDays(12)],
            ['voter_id' => 14, 'voter_identifier' => '1000014', 'full_name' => 'Test Voter Fourteen', 'from_constituency' => 'Wau Central', 'to_constituency' => 'Jur River', 'from_state' => 'Western Bahr el Ghazal', 'to_state' => 'Western Bahr el Ghazal', 'reason' => 'Personal reasons', 'status' => 'pending', 'created_at' => now()->subDays(6)],
            ['voter_id' => 15, 'voter_identifier' => '1000015', 'full_name' => 'Test Voter Fifteen', 'from_constituency' => 'Malakal Town', 'to_constituency' => 'Baliet', 'from_state' => 'Upper Nile', 'to_state' => 'Upper Nile', 'reason' => 'Agricultural land near family', 'status' => 'rejected', 'admin_notes' => 'Voter already registered at destination', 'reviewed_at' => $now, 'created_at' => now()->subDays(30)],
        ];

        foreach ($transfers as $t) {
            DB::table('nec_voter_transfers')->insert(array_merge($t, ['updated_at' => $now]));
        }

        // D. Activity Logs (50)
        $actions = [
            ['action' => 'login', 'details' => 'Logged into admin panel', 'entity_type' => 'auth'],
            ['action' => 'create', 'details' => 'Created news article', 'entity_type' => 'news'],
            ['action' => 'update', 'details' => 'Updated voter registration details', 'entity_type' => 'voter'],
            ['action' => 'create', 'details' => 'Registered new voter', 'entity_type' => 'voter'],
            ['action' => 'approve', 'details' => 'Approved voter transfer request', 'entity_type' => 'transfer'],
            ['action' => 'reject', 'details' => 'Rejected voter transfer request', 'entity_type' => 'transfer'],
            ['action' => 'create', 'details' => 'Created new user account', 'entity_type' => 'user'],
            ['action' => 'update', 'details' => 'Updated system settings', 'entity_type' => 'settings'],
            ['action' => 'delete', 'details' => 'Deleted draft announcement', 'entity_type' => 'announcement'],
            ['action' => 'export', 'details' => 'Exported voter data to CSV', 'entity_type' => 'voter'],
            ['action' => 'create', 'details' => 'Published press release', 'entity_type' => 'news'],
            ['action' => 'update', 'details' => 'Updated commissioner profile', 'entity_type' => 'commissioner'],
            ['action' => 'create', 'details' => 'Created new announcement', 'entity_type' => 'announcement'],
            ['action' => 'approve', 'details' => 'Approved observer application', 'entity_type' => 'observer'],
            ['action' => 'login', 'details' => 'Logged into admin panel', 'entity_type' => 'auth'],
        ];

        $userEmails = DB::table('nec_users')->pluck('email')->toArray();
        $ips = ['192.168.1.10', '10.0.0.5', '172.16.0.1', '192.168.2.20', '10.10.1.1'];

        for ($i = 0; $i < 50; $i++) {
            $a = $actions[array_rand($actions)];
            $userEmail = $userEmails[array_rand($userEmails)];
            $daysAgo = rand(0, 30);
            $hoursAgo = rand(0, 23);

            DB::table('nec_activity_logs')->insert([
                'user_email' => $userEmail,
                'action' => $a['action'],
                'entity_type' => $a['entity_type'],
                'details' => $a['details'],
                'ip_address' => $ips[array_rand($ips)],
                'created_at' => now()->subDays($daysAgo)->subHours($hoursAgo),
            ]);
        }

        $this->command->info('User System seeder completed successfully!');
        $this->command->info('Users: ' . DB::table('nec_users')->count());
        $this->command->info('Voter Accounts: ' . DB::table('nec_voter_accounts')->count());
        $this->command->info('Transfers: ' . DB::table('nec_voter_transfers')->count());
        $this->command->info('Activity Logs: ' . DB::table('nec_activity_logs')->count());
    }
}
