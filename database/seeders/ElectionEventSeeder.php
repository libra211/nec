<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ElectionEventSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $events = [
            [
                'title' => '2026 Presidential Election',
                'event_type' => 'presidential',
                'description' => 'National presidential election to elect the President and Vice President of the Republic of South Sudan.',
                'start_date' => '2026-12-15',
                'end_date' => '2026-12-15',
                'location' => 'National',
                'status' => 'active',
            ],
            [
                'title' => '2026 National Legislative Assembly Elections',
                'event_type' => 'parliamentary',
                'description' => 'Elections for the 332-member National Legislative Assembly across all 102 constituencies.',
                'start_date' => '2026-12-15',
                'end_date' => '2026-12-16',
                'location' => 'National',
                'status' => 'active',
            ],
            [
                'title' => '2026 State Assembly Elections - Equatoria',
                'event_type' => 'state',
                'description' => 'State-level legislative elections across all three Equatoria states.',
                'start_date' => '2026-12-20',
                'end_date' => '2026-12-20',
                'location' => 'Equatoria Region',
                'status' => 'active',
            ],
            [
                'title' => '2026 State Assembly Elections - Greater Upper Nile',
                'event_type' => 'state',
                'description' => 'State-level legislative elections across Jonglei, Unity, and Upper Nile states.',
                'start_date' => '2026-12-22',
                'end_date' => '2026-12-22',
                'location' => 'Greater Upper Nile',
                'status' => 'active',
            ],
            [
                'title' => '2026 State Assembly Elections - Bahr el Ghazal',
                'event_type' => 'state',
                'description' => 'State-level legislative elections across Bahr el Ghazal region.',
                'start_date' => '2026-12-24',
                'end_date' => '2026-12-24',
                'location' => 'Bahr el Ghazal',
                'status' => 'active',
            ],
            [
                'title' => '2024 Census-Linked Voter Registration Pilot',
                'event_type' => 'registration',
                'description' => 'Pilot voter registration exercise conducted alongside population census activities.',
                'start_date' => '2024-06-01',
                'end_date' => '2024-08-31',
                'location' => 'National',
                'status' => 'inactive',
            ],
        ];

        foreach ($events as $e) {
            DB::table('nec_election_events')->updateOrInsert(
                ['title' => $e['title']],
                array_merge($e, ['created_at' => $now])
            );
        }

        $this->command->info('ElectionEventSeeder: Events: ' . DB::table('nec_election_events')->count());
    }
}
