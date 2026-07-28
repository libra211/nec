<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
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

            DB::table('nec_voters')->updateOrInsert(
                ['voter_id' => $voterId],
                [
                    'voter_id' => $voterId,
                    'full_name' => $fullName,
                    'gender' => $gender,
                    'dob' => "{$dobYear}-{$dobMonth}-{$dobDay}",
                    'state' => $state,
                    'constituency' => $this->getRandomConstituency($state),
                    'county' => $this->getRandomCounty($state),
                    'national_id' => 'SSN' . str_pad(rand(1000000, 9999999), 7, '0', STR_PAD_LEFT),
                    'phone' => '+2119' . str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT),
                    'status' => $status,
                    'registered_at' => $regDate->format('Y-m-d H:i:s'),
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }

        $this->command->info('VoterDemoSeeder: Voters created: ' . DB::table('nec_voters')->count());
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
