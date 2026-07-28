<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AgentSeeder extends Seeder
{
    public function run(): void
    {
        $agents = [
            [
                'first_name' => 'James', 'last_name' => 'Deng',
                'phone' => '+211912345001', 'email' => 'james.deng@nec.gov.ss',
                'national_id' => 'SSN-1001', 'title' => 'Senior Registration Officer',
                'state' => 'Central Equatoria', 'county' => 'Juba County',
                'constituency' => 'Juba I', 'payam' => 'Juba Central Payam',
                'assigned_state' => 'Central Equatoria', 'assigned_county' => 'Juba County',
                'assigned_constituency' => 'Juba I', 'status' => 'active', 'voters_registered' => 87,
            ],
            [
                'first_name' => 'Mary', 'last_name' => 'Akello',
                'phone' => '+211912345002', 'email' => 'mary.akello@nec.gov.ss',
                'national_id' => 'SSN-1002', 'title' => 'Registration Officer',
                'state' => 'Central Equatoria', 'county' => 'Juba County',
                'constituency' => 'Juba II', 'payam' => 'Kator Payam',
                'assigned_state' => 'Central Equatoria', 'assigned_county' => 'Juba County',
                'assigned_constituency' => 'Juba II', 'status' => 'active', 'voters_registered' => 64,
            ],
            [
                'first_name' => 'Peter', 'last_name' => 'Gatkuoth',
                'phone' => '+211912345003', 'email' => 'peter.gatkuoth@nec.gov.ss',
                'national_id' => 'SSN-1003', 'title' => 'Registration Officer',
                'state' => 'Upper Nile', 'county' => 'Malakal County',
                'constituency' => 'Malakal', 'payam' => 'Malakal Payam',
                'assigned_state' => 'Upper Nile', 'assigned_county' => 'Malakal County',
                'assigned_constituency' => 'Malakal', 'status' => 'active', 'voters_registered' => 112,
            ],
            [
                'first_name' => 'Sarah', 'last_name' => 'Nyabuoy',
                'phone' => '+211912345004', 'email' => 'sarah.nyabuoy@nec.gov.ss',
                'national_id' => 'SSN-1004', 'title' => 'Senior Registration Officer',
                'state' => 'Jonglei', 'county' => 'Bor South County',
                'constituency' => 'Bor South', 'payam' => 'Bor Payam',
                'assigned_state' => 'Jonglei', 'assigned_county' => 'Bor South County',
                'assigned_constituency' => 'Bor South', 'status' => 'active', 'voters_registered' => 95,
            ],
            [
                'first_name' => 'David', 'last_name' => 'Mawien',
                'phone' => '+211912345005', 'email' => 'david.mawien@nec.gov.ss',
                'national_id' => 'SSN-1005', 'title' => 'Registration Officer',
                'state' => 'Warrap', 'county' => 'Warrap',
                'constituency' => 'Warrap East', 'payam' => 'Kuajok Payam',
                'assigned_state' => 'Warrap', 'assigned_county' => 'Warrap',
                'assigned_constituency' => 'Warrap East', 'status' => 'active', 'voters_registered' => 53,
            ],
            [
                'first_name' => 'Grace', 'last_name' => 'Andrea',
                'phone' => '+211912345006', 'email' => 'grace.andrea@nec.gov.ss',
                'national_id' => 'SSN-1006', 'title' => 'Registration Officer',
                'state' => 'Western Equatoria', 'county' => 'Yambio',
                'constituency' => 'Yambio', 'payam' => 'Yambio Payam',
                'assigned_state' => 'Western Equatoria', 'assigned_county' => 'Yambio',
                'assigned_constituency' => 'Yambio', 'status' => 'active', 'voters_registered' => 41,
            ],
            [
                'first_name' => 'Emmanuel', 'last_name' => 'Kuol',
                'phone' => '+211912345007', 'email' => 'emmanuel.kuol@nec.gov.ss',
                'national_id' => 'SSN-1007', 'title' => 'Registration Officer',
                'state' => 'Lakes', 'county' => 'Rumbek Centre',
                'constituency' => 'Rumbek East', 'payam' => 'Rumbek Payam',
                'assigned_state' => 'Lakes', 'assigned_county' => 'Rumbek Centre',
                'assigned_constituency' => 'Rumbek East', 'status' => 'inactive', 'voters_registered' => 28,
            ],
            [
                'first_name' => 'Esther', 'last_name' => 'John',
                'phone' => '+211912345008', 'email' => 'esther.john@nec.gov.ss',
                'national_id' => 'SSN-1008', 'title' => 'Senior Registration Officer',
                'state' => 'Northern Bahr el Ghazal', 'county' => 'Aweil Center County',
                'constituency' => 'Aweil Centre', 'payam' => 'Aweil Payam',
                'assigned_state' => 'Northern Bahr el Ghazal', 'assigned_county' => 'Aweil Center County',
                'assigned_constituency' => 'Aweil Centre', 'status' => 'active', 'voters_registered' => 76,
            ],
        ];

        foreach ($agents as $agent) {
            $agent['created_at'] = now();
            $agent['updated_at'] = now();
            DB::table('nec_agents')->updateOrInsert(
                ['phone' => $agent['phone']],
                $agent
            );
        }
    }
}
