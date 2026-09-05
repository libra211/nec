<?php

namespace Database\Seeders;

use App\Helpers\NecHelper;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class VoterDemoSeeder extends Seeder
{
    private array $states = [
        'Central Equatoria', 'Eastern Equatoria', 'Western Equatoria',
        'Jonglei', 'Unity', 'Upper Nile', 'Lakes', 'Warrap',
        'Northern Bahr el Ghazal', 'Western Bahr el Ghazal',
    ];

    private array $maleFirstNames = [
        'James', 'Peter', 'John', 'David', 'Daniel', 'Samuel', 'Joseph', 'Michael', 'Simon', 'Paul',
        'Stephen', 'Martin', 'Charles', 'Victor', 'Patrick', 'Francis', 'Anthony', 'George', 'Thomas', 'Robert',
        'Emmanuel', 'Mark', 'Isaac', 'Moses', 'Jacob', 'Benjamin', 'Andrew', 'Timothy', 'Richard', 'Nelson',
        'Obed', 'Mading', 'Kuol', 'Bol', 'Deng', 'Kur', 'Gatluak', 'Lual', 'Athian', 'Mou',
    ];

    private array $femaleFirstNames = [
        'Mary', 'Sarah', 'Grace', 'Agnes', 'Ruth', 'Esther', 'Rebecca', 'Hannah', 'Elizabeth', 'Martha',
        'Nancy', 'Florence', 'Patricia', 'Catherine', 'Theresa', 'Julia', 'Janet', 'Lilian', 'Betty', 'Rose',
        'Alice', 'Sandra', 'Diana', 'Nyaluak', 'Nyabol', 'Nyamal', 'Nyakuoth', 'Nyabuoy', 'Nyadak', 'Nyakoak',
        'Aisha', 'Halima', 'Miriam', 'Naomi', 'Edna', 'Vera', 'Gladys', 'Irene', 'Janet', 'Mercy',
    ];

    private array $lastNames = [
        'Kuir', 'Deng', 'Kuol', 'Bol', 'Atem', 'Garang', 'Kiir', 'Machar', 'Wani', 'Lado',
        'Arop', 'Malual', 'Akec', 'Akol', 'Luk', 'Okuon', 'Bek', 'Laku', 'Taban', 'Jok',
        'Kuany', 'Gatluak', 'Nyandeng', 'Nyakim', 'Lual', 'Maker', 'Athian', 'Mou', 'Par', 'Akuek',
        'Yor', 'Abraham', 'Khalil', 'Ibrahim', 'Hassan', 'Ali', 'Omar', 'Osman', 'Adam', 'Abdel',
        'Sebit', 'Nyamwiza', 'Babu', 'Inyu', 'Lopidia', 'Nyuot', 'Puok', 'Ture', 'Galuak', 'Stephen',
    ];

    public function run(): void
    {
        $now = now();
        $states = $this->states;

        for ($i = 1; $i <= 200; $i++) {
            $gender = $i % 3 === 0 ? 'F' : 'M';
            $state = $states[array_rand($states)];
            $firstName = $gender === 'M'
                ? $this->maleFirstNames[array_rand($this->maleFirstNames)]
                : $this->femaleFirstNames[array_rand($this->femaleFirstNames)];
            $lastName = $this->lastNames[array_rand($this->lastNames)];
            $fullName = $firstName . ' ' . $lastName;

            $dobYear = rand(1960, 2005);
            $dobMonth = str_pad(rand(1, 12), 2, '0', STR_PAD_LEFT);
            $dobDay = str_pad(rand(1, 28), 2, '0', STR_PAD_LEFT);

            $statuses = ['active', 'active', 'active', 'active', 'inactive'];
            $status = $statuses[array_rand($statuses)];

            $regDate = $now->subDays(rand(1, 120));
            $voterId = 'NEC26' . $gender . str_pad($i, 6, '0', STR_PAD_LEFT);
            $dob = "{$dobYear}-{$dobMonth}-{$dobDay}";

            DB::table('nec_voters')->updateOrInsert(
                ['voter_id' => $voterId],
                [
                    'voter_id' => $voterId,
                    'full_name' => $fullName,
                    'gender' => $gender,
                    'dob' => $dob,
                    'state' => $state,
                    'constituency' => $this->getRandomConstituency($state),
                    'county' => $this->getRandomCounty($state),
                    'national_id' => 'SSN' . str_pad(rand(1000000, 9999999), 7, '0', STR_PAD_LEFT),
                    'phone' => '+2119' . str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT),
                    'status' => $status,
                    'eligibility_date' => NecHelper::eligibility_date(Carbon::parse($dob))?->format('Y-m-d'),
                    'eligible_to_vote' => NecHelper::age_at(Carbon::parse($dob)) >= NecHelper::voting_age(),
                    'pre_registered' => Carbon::parse($dob)->age < NecHelper::voting_age(),
                    'registered_at' => $regDate->format('Y-m-d H:i:s'),
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }

        $this->command->info('VoterDemoSeeder: Voters created: ' . DB::table('nec_voters')->count());

        $this->seedDiasporaVoters($now);
    }

    private function seedDiasporaVoters($now): void
    {
        $missions = DB::table('nec_diaspora_missions')
            ->where('nec_diaspora_missions.status', 'active')
            ->join('nec_countries', 'nec_countries.id', '=', 'nec_diaspora_missions.country_id')
            ->select('nec_diaspora_missions.id as mission_id', 'nec_diaspora_missions.city', 'nec_diaspora_missions.name as mission_name', 'nec_countries.id as country_id', 'nec_countries.name as country_name', 'nec_countries.nationality')
            ->get();

        if ($missions->isEmpty()) {
            $this->command->warn('VoterDemoSeeder: No diaspora missions found, skipping diaspora voters.');
            return;
        }

        $languages = ['English', 'Arabic', 'English', 'English', 'Other'];

        for ($i = 1; $i <= 60; $i++) {
            $mission = $missions->random();
            $gender = rand(0, 1) === 0 ? 'M' : 'F';
            $firstName = $gender === 'M'
                ? $this->maleFirstNames[array_rand($this->maleFirstNames)]
                : $this->femaleFirstNames[array_rand($this->femaleFirstNames)];
            $lastName = $this->lastNames[array_rand($this->lastNames)];
            $fullName = $firstName . ' ' . $lastName;

            $dobYear = rand(1955, 2005);
            $dob = sprintf('%04d-%02d-%02d', $dobYear, rand(1, 12), rand(1, 28));
            $statuses = ['active', 'active', 'active', 'active', 'inactive'];
            $voterId = 'NEC26' . $gender . 'D' . str_pad($i, 6, '0', STR_PAD_LEFT);

            DB::table('nec_voters')->updateOrInsert(
                ['voter_id' => $voterId],
                [
                    'voter_id' => $voterId,
                    'full_name' => $fullName,
                    'gender' => $gender,
                    'dob' => $dob,
                    'country_id' => $mission->country_id,
                    'country_name' => $mission->country_name,
                    'nationality' => $mission->nationality ?: 'South Sudanese',
                    'city' => $mission->city,
                    'address' => rand(0, 1) ? $mission->mission_name . ', ' . $mission->city : rand(1, 500) . ' Street, ' . $mission->city,
                    'postal_code' => rand(0, 1) ? (string) rand(1000, 99999) : null,
                    'is_diaspora' => 1,
                    'diaspora_mission_id' => $mission->mission_id,
                    'passport_number' => 'SS-P' . strtoupper(substr($lastName, 0, 2)) . str_pad(rand(100000, 999999), 6, '0', STR_PAD_LEFT),
                    'phone' => '+1' . str_pad(rand(2000000000, 9999999999), 10, '0', STR_PAD_LEFT),
                    'email' => strtolower($firstName . '.' . $lastName . rand(1, 99)) . '@gmail.com',
                    'preferred_language' => $languages[array_rand($languages)],
                    'document_type' => 'passport',
                    'polling_station' => $mission->mission_name . ', ' . $mission->city . ', ' . $mission->country_name,
                    'status' => $statuses[array_rand($statuses)],
                    'eligibility_date' => NecHelper::eligibility_date(Carbon::parse($dob))?->format('Y-m-d'),
                    'eligible_to_vote' => NecHelper::age_at(Carbon::parse($dob)) >= NecHelper::voting_age(),
                    'pre_registered' => Carbon::parse($dob)->age < NecHelper::voting_age(),
                    'registered_at' => $now->subDays(rand(1, 120))->format('Y-m-d H:i:s'),
                    'verified_at' => rand(0, 1) ? $now->subDays(rand(1, 60)) : null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }

        $diasporaCount = DB::table('nec_voters')->where('is_diaspora', 1)->count();
        $this->command->info("VoterDemoSeeder: Diaspora voters created: {$diasporaCount}");
    }

    private function getRandomConstituency(string $state): string
    {
        $constituencies = [
            'Central Equatoria' => ['Juba City', 'Kator', 'Kajo-Keji', 'Yei Town', 'Terekeka', 'Lobonok', 'Budi'],
            'Eastern Equatoria' => ['Torit Town', 'Kapoeta East', 'Magwi', 'Napak', 'Ikwoto', 'Lopa/Lafon'],
            'Western Equatoria' => ['Yambio Town', 'Nzara', 'Maridi', 'Amadi', 'Mundri East', 'Ezo', 'Tambura'],
            'Jonglei' => ['Bor South', 'Bor North', 'Duk', 'Fangak', 'Ayod', 'Akobo', 'Uror', 'Pibor'],
            'Unity' => ['Bentiu Town', 'Mayom', 'Koch', 'Rubkona', 'Leer', 'Panyijar', 'Abiemnom'],
            'Upper Nile' => ['Malakal Town', 'Renk', 'Baliet', 'Longochuk', 'Nasir', 'Ulang', 'Panyikang'],
            'Lakes' => ['Rumbek Centre', 'Rumbek East', 'Wulu', 'Yirol East', 'Cueibet', 'Awerial North'],
            'Warrap' => ['Kuajok', 'Warrap Town', 'Tong East', 'Thiet', 'Gogrial East', 'Twic East'],
            'Northern Bahr el Ghazal' => ['Aweil Centre', 'Aweil East', 'Aweil North', 'Gog West', 'Kuac South', 'Mayom'],
            'Western Bahr el Ghazal' => ['Wau Centre', 'Wau East', 'Jur River', 'Raga', 'Bussere', 'Deim Zubeir'],
        ];

        $stateConstituencies = $constituencies[$state] ?? ['Unknown'];
        return $stateConstituencies[array_rand($stateConstituencies)];
    }

    private function getRandomCounty(string $state): string
    {
        $counties = [
            'Central Equatoria' => ['Juba', 'Kajo-Keji', 'Yei', 'Terekeka', 'Lobonok'],
            'Eastern Equatoria' => ['Torit', 'Kapoeta', 'Magwi', 'Napak', 'Ikwoto'],
            'Western Equatoria' => ['Yambio', 'Nzara', 'Maridi', 'Amadi', 'Mundri'],
            'Jonglei' => ['Bor', 'Duk', 'Fangak', 'Ayod', 'Akobo', 'Pibor'],
            'Unity' => ['Bentiu', 'Mayom', 'Koch', 'Rubkona', 'Leer'],
            'Upper Nile' => ['Malakal', 'Renk', 'Baliet', 'Nasir', 'Ulang'],
            'Lakes' => ['Rumbek Centre', 'Rumbek East', 'Wulu', 'Yirol', 'Cueibet'],
            'Warrap' => ['Kuajok', 'Warrap', 'Tong', 'Thiet', 'Gogrial'],
            'Northern Bahr el Ghazal' => ['Aweil Centre', 'Aweil East', 'Gog', 'Kuac', 'Mayom'],
            'Western Bahr el Ghazal' => ['Wau', 'Jur River', 'Raga', 'Bussere'],
        ];

        $stateCounties = $counties[$state] ?? ['Unknown'];
        return $stateCounties[array_rand($stateCounties)];
    }
}
