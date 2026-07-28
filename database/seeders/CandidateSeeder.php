<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CandidateSeeder extends Seeder
{
    private array $parties = [
        ['name' => 'Sudan People\'s Liberation Movement', 'acronym' => 'SPLM', 'color' => '#2E8B57'],
        ['name' => 'Sudan People\'s Liberation Movement in Opposition', 'acronym' => 'SPLM-IO', 'color' => '#1a3c8f'],
        ['name' => 'South Sudan Opposition Alliance', 'acronym' => 'SSOA', 'color' => '#8B0000'],
        ['name' => 'National Congress Party', 'acronym' => 'NCP', 'color' => '#D4AF37'],
        ['name' => 'Sudan African National Union', 'acronym' => 'SANU', 'color' => '#800080'],
        ['name' => 'United South Sudanese Party', 'acronym' => 'USSP', 'color' => '#FF8C00'],
        ['name' => 'People\'s Progressive Party', 'acronym' => 'PPP', 'color' => '#800000'],
        ['name' => 'South Sudan Democratic Movement', 'acronym' => 'SSDM', 'color' => '#008080'],
        ['name' => 'Federal Party of South Sudan', 'acronym' => 'FPSS', 'color' => '#000080'],
        ['name' => 'Democratic Change Party', 'acronym' => 'DCP', 'color' => '#DC143C'],
    ];

    private array $maleNames = [
        'James', 'Peter', 'John', 'David', 'Daniel', 'Samuel', 'Joseph', 'Michael', 'Simon', 'Paul',
        'Stephen', 'Martin', 'Charles', 'Victor', 'Patrick', 'Francis', 'Anthony', 'George', 'Thomas', 'Robert',
        'Emmanuel', 'Mark', 'Isaac', 'Moses', 'Jacob', 'Benjamin', 'Andrew', 'Timothy', 'Richard', 'Nelson',
    ];

    private array $lastNames = [
        'Kuir', 'Deng', 'Kuol', 'Bol', 'Atem', 'Garang', 'Kiir', 'Machar', 'Wani', 'Lado',
        'Arop', 'Malual', 'Akec', 'Akol', 'Luk', 'Okuon', 'Bek', 'Laku', 'Taban', 'Jok',
        'Kuany', 'Gatluak', 'Par', 'Akuek', 'Yor', 'Abraham', 'Sebit', 'Babu', 'Maker', 'Athian',
    ];

    private array $states = [
        'Central Equatoria' => ['Juba City', 'Kator', 'Kajo-Keji', 'Yei Town', 'Terekeka'],
        'Eastern Equatoria' => ['Torit Town', 'Kapoeta East', 'Magwi', 'Napak'],
        'Western Equatoria' => ['Yambio Town', 'Nzara', 'Maridi', 'Mundri East'],
        'Jonglei' => ['Bor South', 'Bor North', 'Duk', 'Fangak', 'Ayod', 'Akobo'],
        'Unity' => ['Bentiu Town', 'Mayom', 'Koch', 'Rubkona', 'Leer'],
        'Upper Nile' => ['Malakal Town', 'Renk', 'Baliet', 'Nasir', 'Ulang'],
        'Lakes' => ['Rumbek Centre', 'Rumbek East', 'Wulu', 'Yirol East'],
        'Warrap' => ['Kuajok', 'Warrap Town', 'Tong East', 'Thiet'],
        'Northern Bahr el Ghazal' => ['Aweil Centre', 'Aweil East', 'Gog West', 'Kuac South'],
        'Western Bahr el Ghazal' => ['Wau Centre', 'Wau East', 'Jur River', 'Raga'],
    ];

    public function run(): void
    {
        $now = now();

        foreach ($this->parties as $p) {
            DB::table('nec_political_parties')->updateOrInsert(
                ['acronym' => $p['acronym']],
                ['name' => $p['name'], 'acronym' => $p['acronym'], 'color' => $p['color'], 'status' => 'active', 'created_at' => $now]
            );
        }

        $partyIds = DB::table('nec_political_parties')->pluck('id')->toArray();
        if (empty($partyIds)) return;

        $candidates = [];
        $count = 0;

        $constituencies = DB::table('nec_constituencies')->get();
        foreach ($constituencies as $constituency) {
            $numCandidates = rand(2, 4);
            for ($j = 0; $j < $numCandidates; $j++) {
                $count++;
                $partyId = $partyIds[array_rand($partyIds)];
                $firstName = $this->maleNames[array_rand($this->maleNames)];
                $lastName = $this->lastNames[array_rand($this->lastNames)];
                $status = $count % 5 === 0 ? 'inactive' : 'active';

                $candidates[] = [
                    'name' => $firstName . ' ' . $lastName,
                    'party_id' => $partyId,
                    'position' => 'Member of Parliament',
                    'constituency' => $constituency->name,
                    'state' => $constituency->state,
                    'status' => $status,
                    'created_at' => $now,
                ];

                if ($count >= 55) break 2;
            }
        }

        foreach ($candidates as $c) {
            DB::table('nec_candidates')->insert($c);
        }

        $this->command->info('CandidateSeeder: Parties: ' . DB::table('nec_political_parties')->count());
        $this->command->info('Candidates: ' . DB::table('nec_candidates')->count());
    }
}
