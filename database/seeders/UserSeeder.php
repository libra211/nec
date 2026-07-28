<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $password = Hash::make('password');

        $users = [
            ['email' => 'admin@nec.gov.ss', 'name' => 'Administrator', 'password' => $password, 'role' => 'super_admin', 'department' => 'Administration', 'state' => 'Central Equatoria', 'position' => 'System Administrator', 'employee_id' => 'NEC-001', 'status' => 'active', 'phone' => '+211912000001', 'created_at' => $now],
            ['email' => 'superadmin@nec.gov.ss', 'name' => 'Super Administrator', 'password' => $password, 'role' => 'super_admin', 'department' => 'Administration', 'state' => 'Central Equatoria', 'position' => 'Super Administrator', 'employee_id' => 'NEC-002', 'status' => 'active', 'phone' => '+211912000002', 'created_at' => $now],
            ['email' => 'coord.ce@nec.gov.ss', 'name' => 'James Laku Lado', 'password' => $password, 'role' => 'state_coordinator', 'department' => 'Election Operations', 'state' => 'Central Equatoria', 'position' => 'State Coordinator', 'employee_id' => 'NEC-020', 'status' => 'active', 'phone' => '+211912000020', 'created_at' => $now],
            ['email' => 'coord.ee@nec.gov.ss', 'name' => 'Sarah Nimirio Lokai', 'password' => $password, 'role' => 'state_coordinator', 'department' => 'Election Operations', 'state' => 'Eastern Equatoria', 'position' => 'State Coordinator', 'employee_id' => 'NEC-021', 'status' => 'active', 'phone' => '+211912000021', 'created_at' => $now],
            ['email' => 'coord.we@nec.gov.ss', 'name' => 'Paul Taban Mijak', 'password' => $password, 'role' => 'state_coordinator', 'department' => 'Election Operations', 'state' => 'Western Equatoria', 'position' => 'State Coordinator', 'employee_id' => 'NEC-022', 'status' => 'active', 'phone' => '+211912000022', 'created_at' => $now],
            ['email' => 'coord.jong@nec.gov.ss', 'name' => 'Deng Kuol Arop', 'password' => $password, 'role' => 'state_coordinator', 'department' => 'Election Operations', 'state' => 'Jonglei', 'position' => 'State Coordinator', 'employee_id' => 'NEC-023', 'status' => 'active', 'phone' => '+211912000023', 'created_at' => $now],
            ['email' => 'coord.unity@nec.gov.ss', 'name' => 'Nyaluak Lual Kiir', 'password' => $password, 'role' => 'state_coordinator', 'department' => 'Election Operations', 'state' => 'Unity', 'position' => 'State Coordinator', 'employee_id' => 'NEC-024', 'status' => 'active', 'phone' => '+211912000024', 'created_at' => $now],
            ['email' => 'coord.upn@nec.gov.ss', 'name' => 'Peter Gatbel Kuol', 'password' => $password, 'role' => 'state_coordinator', 'department' => 'Election Operations', 'state' => 'Upper Nile', 'position' => 'State Coordinator', 'employee_id' => 'NEC-025', 'status' => 'active', 'phone' => '+211912000025', 'created_at' => $now],
            ['email' => 'coord.lakes@nec.gov.ss', 'name' => 'Achiek Deng Mone', 'password' => $password, 'role' => 'state_coordinator', 'department' => 'Election Operations', 'state' => 'Lakes', 'position' => 'State Coordinator', 'employee_id' => 'NEC-026', 'status' => 'active', 'phone' => '+211912000026', 'created_at' => $now],
            ['email' => 'coord.nbg@nec.gov.ss', 'name' => 'Mary Akoi Ajack', 'password' => $password, 'role' => 'state_coordinator', 'department' => 'Election Operations', 'state' => 'Northern Bahr el Ghazal', 'position' => 'State Coordinator', 'employee_id' => 'NEC-027', 'status' => 'active', 'phone' => '+211912000027', 'created_at' => $now],
            ['email' => 'coord.wbg@nec.gov.ss', 'name' => 'Stephen Par Kuol', 'password' => $password, 'role' => 'state_coordinator', 'department' => 'Election Operations', 'state' => 'Western Bahr el Ghazal', 'position' => 'State Coordinator', 'employee_id' => 'NEC-028', 'status' => 'active', 'phone' => '+211912000028', 'created_at' => $now],
            ['email' => 'coord.warrap@nec.gov.ss', 'name' => 'Kuol Athian Mou', 'password' => $password, 'role' => 'state_coordinator', 'department' => 'Election Operations', 'state' => 'Warrap', 'position' => 'State Coordinator', 'employee_id' => 'NEC-029', 'status' => 'active', 'phone' => '+211912000029', 'created_at' => $now],
            ['email' => 'const.juba1@nec.gov.ss', 'name' => 'Michael Majak Akol', 'password' => $password, 'role' => 'constituency_officer', 'department' => 'Constituency Management', 'state' => 'Central Equatoria', 'position' => 'Constituency Officer - Juba', 'employee_id' => 'NEC-030', 'status' => 'active', 'phone' => '+211912000030', 'created_at' => $now],
            ['email' => 'const.bor@nec.gov.ss', 'name' => 'Nyabol Khor Puok', 'password' => $password, 'role' => 'constituency_officer', 'department' => 'Constituency Management', 'state' => 'Jonglei', 'position' => 'Constituency Officer - Bor', 'employee_id' => 'NEC-031', 'status' => 'active', 'phone' => '+211912000031', 'created_at' => $now],
            ['email' => 'const.wau@nec.gov.ss', 'name' => 'Simon Akuek Bol', 'password' => $password, 'role' => 'constituency_officer', 'department' => 'Constituency Management', 'state' => 'Western Bahr el Ghazal', 'position' => 'Constituency Officer - Wau', 'employee_id' => 'NEC-032', 'status' => 'active', 'phone' => '+211912000032', 'created_at' => $now],
            ['email' => 'const.malakal@nec.gov.ss', 'name' => 'Elizabeth Nyabuoy Galuak', 'password' => $password, 'role' => 'constituency_officer', 'department' => 'Constituency Management', 'state' => 'Upper Nile', 'position' => 'Constituency Officer - Malakal', 'employee_id' => 'NEC-033', 'status' => 'active', 'phone' => '+211912000033', 'created_at' => $now],
            ['email' => 'const.yambio@nec.gov.ss', 'name' => 'Andrea Sabastiano Ture', 'password' => $password, 'role' => 'constituency_officer', 'department' => 'Constituency Management', 'state' => 'Western Equatoria', 'position' => 'Constituency Officer - Yambio', 'employee_id' => 'NEC-034', 'status' => 'inactive', 'phone' => '+211912000034', 'created_at' => $now],
            ['email' => 'reg.ce1@nec.gov.ss', 'name' => 'Joseph Okech Inyu', 'password' => $password, 'role' => 'registration_officer', 'department' => 'Voter Registration', 'state' => 'Central Equatoria', 'position' => 'Registration Officer', 'employee_id' => 'NEC-040', 'status' => 'active', 'phone' => '+211912000040', 'created_at' => $now],
            ['email' => 'reg.ee1@nec.gov.ss', 'name' => 'Agnes Lopidia Ekiru', 'password' => $password, 'role' => 'registration_officer', 'department' => 'Voter Registration', 'state' => 'Eastern Equatoria', 'position' => 'Registration Officer', 'employee_id' => 'NEC-041', 'status' => 'active', 'phone' => '+211912000041', 'created_at' => $now],
            ['email' => 'reg.jon1@nec.gov.ss', 'name' => 'David Gai Nyuot', 'password' => $password, 'role' => 'registration_officer', 'department' => 'Voter Registration', 'state' => 'Jonglei', 'position' => 'Registration Officer', 'employee_id' => 'NEC-042', 'status' => 'active', 'phone' => '+211912000042', 'created_at' => $now],
            ['email' => 'reg.uni1@nec.gov.ss', 'name' => 'Grace Nyabuoy Gatluak', 'password' => $password, 'role' => 'registration_officer', 'department' => 'Voter Registration', 'state' => 'Unity', 'position' => 'Registration Officer', 'employee_id' => 'NEC-043', 'status' => 'active', 'phone' => '+211912000043', 'created_at' => $now],
            ['email' => 'reg.lak1@nec.gov.ss', 'name' => 'Samuel Maker Kuol', 'password' => $password, 'role' => 'registration_officer', 'department' => 'Voter Registration', 'state' => 'Lakes', 'position' => 'Registration Officer', 'employee_id' => 'NEC-044', 'status' => 'active', 'phone' => '+211912000044', 'created_at' => $now],
            ['email' => 'poll.ce1@nec.gov.ss', 'name' => 'Charles Wani Stephen', 'password' => $password, 'role' => 'polling_officer', 'department' => 'Polling Operations', 'state' => 'Central Equatoria', 'position' => 'Presiding Officer', 'employee_id' => 'NEC-050', 'status' => 'active', 'phone' => '+211912000050', 'created_at' => $now],
            ['email' => 'poll.ee1@nec.gov.ss', 'name' => 'Florence Anidzo Muzamil', 'password' => $password, 'role' => 'polling_officer', 'department' => 'Polling Operations', 'state' => 'Eastern Equatoria', 'position' => 'Presiding Officer', 'employee_id' => 'NEC-051', 'status' => 'active', 'phone' => '+211912000051', 'created_at' => $now],
            ['email' => 'poll.upn1@nec.gov.ss', 'name' => 'Ibrahim Hassan Abdalla', 'password' => $password, 'role' => 'polling_officer', 'department' => 'Polling Operations', 'state' => 'Upper Nile', 'position' => 'Presiding Officer', 'employee_id' => 'NEC-052', 'status' => 'active', 'phone' => '+211912000052', 'created_at' => $now],
            ['email' => 'data1@nec.gov.ss', 'name' => 'Amina Abuk Deng', 'password' => $password, 'role' => 'data_entry', 'department' => 'Data Management', 'state' => 'Central Equatoria', 'position' => 'Data Entry Clerk', 'employee_id' => 'NEC-060', 'status' => 'active', 'phone' => '+211912000060', 'created_at' => $now],
            ['email' => 'data2@nec.gov.ss', 'name' => 'Bol Stephen Keah', 'password' => $password, 'role' => 'data_entry', 'department' => 'Data Management', 'state' => 'Central Equatoria', 'position' => 'Data Entry Clerk', 'employee_id' => 'NEC-061', 'status' => 'active', 'phone' => '+211912000061', 'created_at' => $now],
            ['email' => 'editor1@nec.gov.ss', 'name' => 'Ruth Nundu Muse', 'password' => $password, 'role' => 'content_editor', 'department' => 'Communications', 'state' => 'Central Equatoria', 'position' => 'Senior Editor', 'employee_id' => 'NEC-070', 'status' => 'active', 'phone' => '+211912000070', 'created_at' => $now],
            ['email' => 'editor2@nec.gov.ss', 'name' => 'Emmanuel Keni Wani', 'password' => $password, 'role' => 'content_editor', 'department' => 'Communications', 'state' => 'Central Equatoria', 'position' => 'Content Editor', 'employee_id' => 'NEC-071', 'status' => 'active', 'phone' => '+211912000071', 'created_at' => $now],
            ['email' => 'itadmin@nec.gov.ss', 'name' => 'Dr. Peter Loku Duku', 'password' => $password, 'role' => 'admin', 'department' => 'Information Technology', 'state' => 'Central Equatoria', 'position' => 'Chief Information Officer', 'employee_id' => 'NEC-080', 'status' => 'active', 'phone' => '+211912000080', 'created_at' => $now],
        ];

        foreach ($users as $user) {
            DB::table('nec_users')->updateOrInsert(
                ['email' => $user['email']],
                array_merge($user, ['last_active' => $now])
            );
        }

        $this->command->info('UserSeeder: Users created: ' . DB::table('nec_users')->count());
    }
}
