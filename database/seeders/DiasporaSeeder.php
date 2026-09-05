<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DiasporaSeeder extends Seeder
{
    // [country_code, name, city, address, code, phone, email, latitude, longitude]
    private array $missions = [
        ['KE', 'High Commission of South Sudan', 'Nairobi', 'Chania Avenue, Kilimani, Nairobi', 'NAIROBI-01', '+254 20 386 4842', 'info@sshc-kenya.org', -1.2921, 36.8219],
        ['KE', 'Consulate General of South Sudan', 'Kakuma', 'Kakuma Refugee Camp, Turkana County', 'KAKUMA-01', '+254 731 234 567', 'kakuma@mission.nec.gov.ss', 3.7212, 34.8672],
        ['UG', 'Embassy of South Sudan', 'Kampala', 'Plot 3, Clement Hill Road, Kampala', 'KAMPALA-01', '+256 414 233 520', 'info@ssembassy-uganda.org', 0.3004, 32.6048],
        ['UG', 'Consulate General of South Sudan', 'Adjumani', 'Adjumani Municipality, Northern Uganda', 'ADJUMANI-01', '+256 392 572 130', 'adjumani@mission.nec.gov.ss', 3.3667, 31.7833],
        ['EG', 'Embassy of South Sudan', 'Cairo', '18 El Batal Ahmed Abdel Aziz St, Dokki, Giza', 'CAIRO-01', '+20 2 333 634 77', 'info@ssembassy-egypt.org', 30.0444, 31.2357],
        ['ET', 'Embassy of South Sudan', 'Addis Ababa', 'Bole, Kebele 03, Addis Ababa', 'ADDIS-01', '+251 11 663 4189', 'info@ssembassy-ethiopia.org', 9.0054, 38.7636],
        ['SD', 'Embassy of South Sudan', 'Khartoum', 'Amarat Street 5, Khartoum', 'KHARTOUM-01', '+249 183 471 232', 'info@ssembassy-sudan.org', 15.5007, 32.5599],
        ['GB', 'Embassy of South Sudan', 'London', '524 Elephant and Castle, London SE1 6JE', 'LONDON-01', '+44 20 7078 8060', 'info@ssembassy-london.org', 51.4952, -0.0987],
        ['US', 'Embassy of South Sudan', 'Washington DC', '1224 O St NW, Washington, DC 20005', 'WASHINGTON-01', '+1 202 381 5568', 'info@ssembassy-usa.org', 38.9072, -77.0369],
        ['US', 'Consulate General of South Sudan', 'Dallas', 'Mirror Centre, 14785 Preston Rd, Dallas, TX', 'DALLAS-01', '+1 214 233 8800', 'dallas@mission.nec.gov.ss', 32.9314, -96.8189],
        ['CA', 'Embassy of South Sudan', 'Ottawa', '55 Sparks Street, Ottawa, ON', 'OTTAWA-01', '+1 613 232 0258', 'info@ssembassy-canada.org', 45.4215, -75.6972],
        ['NG', 'High Commission of South Sudan', 'Abuja', 'No. 27, Agaba Kadodo St, Jabi, Abuja', 'ABUJA-01', '+234 803 456 7890', 'info@sschc-nigeria.org', 9.0579, 7.4951],
        ['ZA', 'High Commission of South Sudan', 'Pretoria', '353 Festival Street, Hatfield, Pretoria', 'PRETORIA-01', '+27 12 342 4622', 'info@sschc-rsa.org', -25.7461, 28.2357],
        ['AE', 'Consulate General of South Sudan', 'Dubai', 'Al Ameera Building, Al Maktoum Road, Deira, Dubai', 'DUBAI-01', '+971 4 268 7333', 'info@sscg-uae.org', 25.2653, 55.2964],
        ['SA', 'Embassy of South Sudan', 'Riyadh', 'Al Sahafah District, Riyadh', 'RIYADH-01', '+966 11 293 1859', 'info@ssembassy-ksa.org', 24.7136, 46.6753],
        ['QA', 'Embassy of South Sudan', 'Doha', 'Al Dafna District, Doha', 'DOHA-01', '+974 4493 1722', 'info@ssembassy-qatar.org', 25.3408, 51.5273],
        ['IN', 'Embassy of South Sudan', 'New Delhi', 'D-3/58, Vasant Vihar, New Delhi', 'DELHI-01', '+91 11 2615 5220', 'info@ssembassy-india.org', 28.5672, 77.1600],
        ['CN', 'Embassy of South Sudan', 'Beijing', 'Building 10, San Li Tun, Beijing', 'BEIJING-01', '+86 10 6532 6753', 'info@ssembassy-china.org', 39.9465, 116.4490],
        ['RU', 'Embassy of South Sudan', 'Moscow', 'Ulitsa Spiryukova 34, Moscow', 'MOSCOW-01', '+7 495 895 2300', 'info@ssembassy-russia.org', 55.7558, 37.6173],
        ['BE', 'Embassy of South Sudan', 'Brussels', 'Avenue Franklin Roosevelt 57, 1050 Brussels', 'BRUSSELS-01', '+32 2 640 0357', 'info@ssembassy-eu.org', 50.8234, 4.3689],
        ['CH', 'Embassy of South Sudan', 'Geneva', 'Rue de Lausanne 120, 1202 Geneva', 'GENEVA-01', '+41 22 341 9850', 'info@ssembassy-geneva.org', 46.2177, 6.1420],
        ['TR', 'Embassy of South Sudan', 'Ankara', 'Oran Mahallesi, Çankaya, Ankara', 'ANKARA-01', '+90 312 439 8620', 'info@ssembassy-turkiye.org', 39.9042, 32.8597],
        ['FR', 'Embassy of South Sudan', 'Paris', '20 Avenue de l\'Opéra, 75001 Paris', 'PARIS-01', '+33 1 42 60 7500', 'info@ssembassy-france.org', 48.8686, 2.3331],
        ['AU', 'Consulate General of South Sudan', 'Canberra', '1 Bowes Street, Phillips, Canberra ACT', 'CANBERRA-01', '+61 2 6230 4100', 'info@sscg-australia.org', -35.3360, 149.0980],
        ['AU', 'Honorary Consulate of South Sudan', 'Sydney', 'Level 12, 1 Macquarie Place, Sydney NSW', 'SYDNEY-01', '+61 2 9251 5300', 'sydney@mission.nec.gov.ss', -33.8522, 151.1985],
    ];

    public function run(): void
    {
        $now = now();
        $countryIds = DB::table('nec_countries')->pluck('id', 'code')->toArray();

        $inserted = 0;
        foreach ($this->missions as $m) {
            [$code, $name, $city, $address, $missionCode, $phone, $email, $lat, $lng] = $m;
            $countryId = $countryIds[$code] ?? null;
            if (!$countryId) {
                $this->command->warn("DiasporaSeeder: country code {$code} not found, skipping {$name}");
                continue;
            }
            DB::table('nec_diaspora_missions')->updateOrInsert(
                ['code' => $missionCode],
                [
                    'country_id' => $countryId,
                    'name' => $name,
                    'city' => $city,
                    'address' => $address,
                    'code' => $missionCode,
                    'phone' => $phone,
                    'email' => $email,
                    'latitude' => $lat,
                    'longitude' => $lng,
                    'status' => 'active',
                    'created_at' => $now,
                    'updated_at' => $now,
                    'deleted_at' => null,
                ]
            );
            $inserted++;
        }

        $this->command->info("DiasporaSeeder: {$inserted} missions synced, total: " . DB::table('nec_diaspora_missions')->count());
    }
}