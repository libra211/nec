<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GeographicSeeder extends Seeder
{
    public function run(): void
    {
        $regions = [
            ['name' => 'Bahr el Ghazal', 'sort_order' => 1, 'status' => 'active'],
            ['name' => 'Equatoria', 'sort_order' => 2, 'status' => 'active'],
            ['name' => 'Greater Upper Nile', 'sort_order' => 3, 'status' => 'active'],
        ];
        foreach ($regions as $r) {
            DB::table('nec_regions')->updateOrInsert(['name' => $r['name']], $r);
        }

        $states = [
            ['name' => 'Northern Bahr el Ghazal', 'code' => 'NBG', 'region' => 'Bahr el Ghazal', 'capital' => 'Aweil'],
            ['name' => 'Western Bahr el Ghazal', 'code' => 'WBG', 'region' => 'Bahr el Ghazal', 'capital' => 'Wau'],
            ['name' => 'Warrap', 'code' => 'WRR', 'region' => 'Bahr el Ghazal', 'capital' => 'Kuajok'],
            ['name' => 'Lakes', 'code' => 'LAK', 'region' => 'Bahr el Ghazal', 'capital' => 'Rumbek'],
            ['name' => 'Central Equatoria', 'code' => 'CES', 'region' => 'Equatoria', 'capital' => 'Juba'],
            ['name' => 'Eastern Equatoria', 'code' => 'EES', 'region' => 'Equatoria', 'capital' => 'Torit'],
            ['name' => 'Western Equatoria', 'code' => 'WES', 'region' => 'Equatoria', 'capital' => 'Yambio'],
            ['name' => 'Jonglei', 'code' => 'JON', 'region' => 'Greater Upper Nile', 'capital' => 'Bor'],
            ['name' => 'Unity', 'code' => 'UNI', 'region' => 'Greater Upper Nile', 'capital' => 'Bentiu'],
            ['name' => 'Upper Nile', 'code' => 'UPN', 'region' => 'Greater Upper Nile', 'capital' => 'Malakal'],
        ];

        foreach ($states as $s) {
            $regionId = DB::table('nec_regions')->where('name', $s['region'])->value('id');
            DB::table('nec_states')->updateOrInsert(
                ['code' => $s['code']],
                ['name' => $s['name'], 'code' => $s['code'], 'region_id' => $regionId, 'capital' => $s['capital']]
            );
        }

        $adminAreas = [
            ['name' => 'Abyei Special Administrative Area', 'code' => 'ABY', 'region' => 'Bahr el Ghazal', 'capital' => 'Abyei'],
            ['name' => 'Greater Pibor Administrative Area', 'code' => 'PIB', 'region' => 'Greater Upper Nile', 'capital' => 'Pibor'],
            ['name' => 'Ruweng Administrative Area', 'code' => 'RWG', 'region' => 'Greater Upper Nile', 'capital' => 'Paloch'],
        ];
        foreach ($adminAreas as $a) {
            $regionId = DB::table('nec_regions')->where('name', $a['region'])->value('id');
            DB::table('nec_states')->updateOrInsert(['name' => $a['name']], [
                'name' => $a['name'], 'code' => $a['code'], 'region_id' => $regionId, 'capital' => $a['capital'], 'type' => 'admin_area', 'status' => 'active',
            ]);
        }

        $counties = [
            // Central Equatoria (state_id 1)
            ['name' => 'Juba County', 'state' => 'CES'],
            ['name' => 'Kajo Keji County', 'state' => 'CES'],
            ['name' => 'Lainya County', 'state' => 'CES'],
            ['name' => 'Morobo County', 'state' => 'CES'],
            ['name' => 'Terekeka County', 'state' => 'CES'],
            ['name' => 'Yei River County', 'state' => 'CES'],
            // Eastern Equatoria (state_id 2)
            ['name' => 'Budi County', 'state' => 'EES'],
            ['name' => 'Ikotos County', 'state' => 'EES'],
            ['name' => 'Kapoeta East County', 'state' => 'EES'],
            ['name' => 'Kapoeta North County', 'state' => 'EES'],
            ['name' => 'Kapoeta South County', 'state' => 'EES'],
            ['name' => 'Lafon County', 'state' => 'EES'],
            ['name' => 'Magwi County', 'state' => 'EES'],
            ['name' => 'Torit County', 'state' => 'EES'],
            // Jonglei (state_id 3)
            ['name' => 'Akobo County', 'state' => 'JON'],
            ['name' => 'Ayod County', 'state' => 'JON'],
            ['name' => 'Bor South County', 'state' => 'JON'],
            ['name' => 'Canal/Pigi County', 'state' => 'JON'],
            ['name' => 'Duk County', 'state' => 'JON'],
            ['name' => 'Fangak County', 'state' => 'JON'],
            ['name' => 'Nyirol County', 'state' => 'JON'],
            ['name' => 'Pibor County', 'state' => 'JON'],
            ['name' => 'Twic East County', 'state' => 'JON'],
            ['name' => 'Uror County', 'state' => 'JON'],
            // Lakes (state_id 4)
            ['name' => 'Awerial County', 'state' => 'LAK'],
            ['name' => 'Cueibet County', 'state' => 'LAK'],
            ['name' => 'Rumbek Centre County', 'state' => 'LAK'],
            ['name' => 'Rumbek East County', 'state' => 'LAK'],
            ['name' => 'Rumbek North County', 'state' => 'LAK'],
            ['name' => 'Wulu County', 'state' => 'LAK'],
            ['name' => 'Yirol East County', 'state' => 'LAK'],
            ['name' => 'Yirol West County', 'state' => 'LAK'],
            // Northern Bahr el Ghazal (state_id 5)
            ['name' => 'Aweil Center County', 'state' => 'NBG'],
            ['name' => 'Aweil East County', 'state' => 'NBG'],
            ['name' => 'Aweil North County', 'state' => 'NBG'],
            ['name' => 'Aweil South County', 'state' => 'NBG'],
            ['name' => 'Aweil West County', 'state' => 'NBG'],
            // Unity (state_id 6)
            ['name' => 'Abiemnhom County', 'state' => 'UNI'],
            ['name' => 'Guit County', 'state' => 'UNI'],
            ['name' => 'Koch County', 'state' => 'UNI'],
            ['name' => 'Leer County', 'state' => 'UNI'],
            ['name' => 'Mayendit County', 'state' => 'UNI'],
            ['name' => 'Mayom County', 'state' => 'UNI'],
            ['name' => 'Panyijiar County', 'state' => 'UNI'],
            ['name' => 'Pariang County', 'state' => 'UNI'],
            ['name' => 'Rubkona County', 'state' => 'UNI'],
            // Upper Nile (state_id 7)
            ['name' => 'Akoka County', 'state' => 'UPN'],
            ['name' => 'Baliet County', 'state' => 'UPN'],
            ['name' => 'Fashoda County', 'state' => 'UPN'],
            ['name' => 'Longochuk County', 'state' => 'UPN'],
            ['name' => 'Maban County', 'state' => 'UPN'],
            ['name' => 'Maiwut County', 'state' => 'UPN'],
            ['name' => 'Malakal County', 'state' => 'UPN'],
            ['name' => 'Manyo County', 'state' => 'UPN'],
            ['name' => 'Melut County', 'state' => 'UPN'],
            ['name' => 'Nasir County', 'state' => 'UPN'],
            ['name' => 'Panyikang County', 'state' => 'UPN'],
            ['name' => 'Renk County', 'state' => 'UPN'],
            ['name' => 'Ulang County', 'state' => 'UPN'],
            // Warrap (state_id 8)
            ['name' => 'Gogrial East County', 'state' => 'WRR'],
            ['name' => 'Gogrial West County', 'state' => 'WRR'],
            ['name' => 'Tonj East County', 'state' => 'WRR'],
            ['name' => 'Tonj North County', 'state' => 'WRR'],
            ['name' => 'Tonj South County', 'state' => 'WRR'],
            ['name' => 'Twic County', 'state' => 'WRR'],
            // Western Bahr el Ghazal (state_id 9)
            ['name' => 'Jur River County', 'state' => 'WBG'],
            ['name' => 'Raga County', 'state' => 'WBG'],
            ['name' => 'Wau County', 'state' => 'WBG'],
            // Western Equatoria (state_id 10)
            ['name' => 'Ezo County', 'state' => 'WES'],
            ['name' => 'Ibba County', 'state' => 'WES'],
            ['name' => 'Maridi County', 'state' => 'WES'],
            ['name' => 'Mundri East County', 'state' => 'WES'],
            ['name' => 'Mundri West County', 'state' => 'WES'],
            ['name' => 'Mvolo County', 'state' => 'WES'],
            ['name' => 'Nagero County', 'state' => 'WES'],
            ['name' => 'Nzara County', 'state' => 'WES'],
            ['name' => 'Tambura County', 'state' => 'WES'],
            ['name' => 'Yambio County', 'state' => 'WES'],
            // Abyei (state_id 11)
            ['name' => 'Abyei County', 'state' => 'ABY'],
            // Greater Pibor (state_id 12)
            ['name' => 'Pibor County', 'state' => 'PIB'],
            ['name' => 'Pochalla County', 'state' => 'PIB'],
            // Ruweng (state_id 13)
            ['name' => 'Abiemnom County', 'state' => 'RWG'],
            ['name' => 'Panriang County', 'state' => 'RWG'],
        ];

        foreach ($counties as $c) {
            $stateId = DB::table('nec_states')->where('code', $c['state'])->value('id');
            if ($stateId) {
                DB::table('nec_counties')->updateOrInsert(
                    ['name' => $c['name']],
                    ['name' => $c['name'], 'state_id' => $stateId, 'status' => 'active']
                );
            }
        }

        $constituencies = [
            ['name' => 'Aweil Centre', 'state' => 'Northern Bahr el Ghazal', 'county' => 'Aweil Centre'],
            ['name' => 'Aweil East', 'state' => 'Northern Bahr el Ghazal', 'county' => 'Aweil East'],
            ['name' => 'Aweil North', 'state' => 'Northern Bahr el Ghazal', 'county' => 'Aweil North'],
            ['name' => 'Aweil South', 'state' => 'Northern Bahr el Ghazal', 'county' => 'Aweil South'],
            ['name' => 'Aweil West', 'state' => 'Northern Bahr el Ghazal', 'county' => 'Aweil West'],
            ['name' => 'Gog West', 'state' => 'Northern Bahr el Ghazal', 'county' => 'Gog West'],
            ['name' => 'Gog East', 'state' => 'Northern Bahr el Ghazal', 'county' => 'Gog East'],
            ['name' => 'Kuac South', 'state' => 'Northern Bahr el Ghazal', 'county' => 'Kuac South'],
            ['name' => 'Kuac North', 'state' => 'Northern Bahr el Ghazal', 'county' => 'Kuac North'],
            ['name' => 'Mayom', 'state' => 'Northern Bahr el Ghazal', 'county' => 'Mayom'],

            ['name' => 'Wau Centre', 'state' => 'Western Bahr el Ghazal', 'county' => 'Wau'],
            ['name' => 'Wau East', 'state' => 'Western Bahr el Ghazal', 'county' => 'Wau'],
            ['name' => 'Wau West', 'state' => 'Western Bahr el Ghazal', 'county' => 'Wau'],
            ['name' => 'Jur River', 'state' => 'Western Bahr el Ghazal', 'county' => 'Jur River'],
            ['name' => 'Raga', 'state' => 'Western Bahr el Ghazal', 'county' => 'Raga'],
            ['name' => 'Bussere', 'state' => 'Western Bahr el Ghazal', 'county' => 'Bussere'],
            ['name' => 'Deim Zubeir', 'state' => 'Western Bahr el Ghazal', 'county' => 'Deim Zubeir'],
            ['name' => 'Kerepi', 'state' => 'Western Bahr el Ghazal', 'county' => 'Kerepi'],
            ['name' => 'Azoora', 'state' => 'Western Bahr el Ghazal', 'county' => 'Azoora'],
            ['name' => 'Gumuria', 'state' => 'Western Bahr el Ghazal', 'county' => 'Gumuria'],

            ['name' => 'Kuajok', 'state' => 'Warrap', 'county' => 'Kuajok'],
            ['name' => 'Warrap Town', 'state' => 'Warrap', 'county' => 'Warrap'],
            ['name' => 'Tong East', 'state' => 'Warrap', 'county' => 'Tong'],
            ['name' => 'Tong West', 'state' => 'Warrap', 'county' => 'Tong'],
            ['name' => 'Rumbek North', 'state' => 'Warrap', 'county' => 'Rumbek'],
            ['name' => 'Thiet', 'state' => 'Warrap', 'county' => 'Thiet'],
            ['name' => 'Yeith', 'state' => 'Warrap', 'county' => 'Yeith'],
            ['name' => 'Gogrial East', 'state' => 'Warrap', 'county' => 'Gogrial'],
            ['name' => 'Gogrial West', 'state' => 'Warrap', 'county' => 'Gogrial'],
            ['name' => 'Twic East', 'state' => 'Warrap', 'county' => 'Twic'],

            ['name' => 'Rumbek Centre', 'state' => 'Lakes', 'county' => 'Rumbek Centre'],
            ['name' => 'Rumbek East', 'state' => 'Lakes', 'county' => 'Rumbek East'],
            ['name' => 'Rumbek North', 'state' => 'Lakes', 'county' => 'Rumbek North'],
            ['name' => 'Rumbek South', 'state' => 'Lakes', 'county' => 'Rumbek South'],
            ['name' => 'Wulu', 'state' => 'Lakes', 'county' => 'Wulu'],
            ['name' => 'Yirol East', 'state' => 'Lakes', 'county' => 'Yirol'],
            ['name' => 'Yirol West', 'state' => 'Lakes', 'county' => 'Yirol'],
            ['name' => 'Awerial North', 'state' => 'Lakes', 'county' => 'Awerial'],
            ['name' => 'Awerial South', 'state' => 'Lakes', 'county' => 'Awerial'],
            ['name' => 'Cueibet', 'state' => 'Lakes', 'county' => 'Cueibet'],

            ['name' => 'Juba City', 'state' => 'Central Equatoria', 'county' => 'Juba'],
            ['name' => 'Kator', 'state' => 'Central Equatoria', 'county' => 'Juba'],
            ['name' => 'Kondokoro', 'state' => 'Central Equatoria', 'county' => 'Juba'],
            ['name' => 'Kajo-Keji', 'state' => 'Central Equatoria', 'county' => 'Kajo-Keji'],
            ['name' => 'Nyamurushia', 'state' => 'Central Equatoria', 'county' => 'Kajo-Keji'],
            ['name' => 'Yei Town', 'state' => 'Central Equatoria', 'county' => 'Yei'],
            ['name' => 'Yei River', 'state' => 'Central Equatoria', 'county' => 'Yei'],
            ['name' => 'Lobonok', 'state' => 'Central Equatoria', 'county' => 'Lobonok'],
            ['name' => 'Terekeka', 'state' => 'Central Equatoria', 'county' => 'Terekeka'],
            ['name' => 'Gondokoro', 'state' => 'Central Equatoria', 'county' => 'Terekeka'],
            ['name' => 'Jubek', 'state' => 'Central Equatoria', 'county' => 'Jubek'],
            ['name' => 'Budi', 'state' => 'Central Equatoria', 'county' => 'Budi'],

            ['name' => 'Torit Town', 'state' => 'Eastern Equatoria', 'county' => 'Torit'],
            ['name' => 'Torit East', 'state' => 'Eastern Equatoria', 'county' => 'Torit'],
            ['name' => 'Kapoeta East', 'state' => 'Eastern Equatoria', 'county' => 'Kapoeta'],
            ['name' => 'Kapoeta West', 'state' => 'Eastern Equatoria', 'county' => 'Kapoeta'],
            ['name' => 'Kapoeta North', 'state' => 'Eastern Equatoria', 'county' => 'Kapoeta'],
            ['name' => 'Magwi', 'state' => 'Eastern Equatoria', 'county' => 'Magwi'],
            ['name' => 'Napak', 'state' => 'Eastern Equatoria', 'county' => 'Napak'],
            ['name' => 'Ikwoto', 'state' => 'Eastern Equatoria', 'county' => 'Ikwoto'],
            ['name' => 'Lopa/Lafon', 'state' => 'Eastern Equatoria', 'county' => 'Lopa'],
            ['name' => 'Budi East', 'state' => 'Eastern Equatoria', 'county' => 'Budi'],

            ['name' => 'Yambio Town', 'state' => 'Western Equatoria', 'county' => 'Yambio'],
            ['name' => 'Yambio East', 'state' => 'Western Equatoria', 'county' => 'Yambio'],
            ['name' => 'Nzara', 'state' => 'Western Equatoria', 'county' => 'Nzara'],
            ['name' => 'Maridi', 'state' => 'Western Equatoria', 'county' => 'Maridi'],
            ['name' => 'Ikwara', 'state' => 'Western Equatoria', 'county' => 'Maridi'],
            ['name' => 'Amadi', 'state' => 'Western Equatoria', 'county' => 'Amadi'],
            ['name' => 'Mundri East', 'state' => 'Western Equatoria', 'county' => 'Mundri'],
            ['name' => 'Mundri West', 'state' => 'Western Equatoria', 'county' => 'Mundri'],
            ['name' => 'Ezo', 'state' => 'Western Equatoria', 'county' => 'Ezo'],
            ['name' => 'Tambura', 'state' => 'Western Equatoria', 'county' => 'Tambura'],

            ['name' => 'Bor South', 'state' => 'Jonglei', 'county' => 'Bor'],
            ['name' => 'Bor North', 'state' => 'Jonglei', 'county' => 'Bor'],
            ['name' => 'Bor West', 'state' => 'Jonglei', 'county' => 'Bor'],
            ['name' => 'Duk', 'state' => 'Jonglei', 'county' => 'Duk'],
            ['name' => 'Fangak', 'state' => 'Jonglei', 'county' => 'Fangak'],
            ['name' => 'Ayod', 'state' => 'Jonglei', 'county' => 'Ayod'],
            ['name' => 'Akobo', 'state' => 'Jonglei', 'county' => 'Akobo'],
            ['name' => 'Uror', 'state' => 'Jonglei', 'county' => 'Uror'],
            ['name' => 'Pibor', 'state' => 'Jonglei', 'county' => 'Pibor'],
            ['name' => 'Old Fangak', 'state' => 'Jonglei', 'county' => 'Old Fangak'],
            ['name' => 'Waat', 'state' => 'Jonglei', 'county' => 'Waat'],
            ['name' => 'Twic East', 'state' => 'Jonglei', 'county' => 'Twic East'],

            ['name' => 'Bentiu Town', 'state' => 'Unity', 'county' => 'Bentiu'],
            ['name' => 'Mayom', 'state' => 'Unity', 'county' => 'Mayom'],
            ['name' => 'Koch', 'state' => 'Unity', 'county' => 'Koch'],
            ['name' => 'Rubkona', 'state' => 'Unity', 'county' => 'Rubkona'],
            ['name' => 'Guit', 'state' => 'Unity', 'county' => 'Guit'],
            ['name' => 'Leer', 'state' => 'Unity', 'county' => 'Leer'],
            ['name' => 'Mayiandit', 'state' => 'Unity', 'county' => 'Mayiandit'],
            ['name' => 'Panyijar', 'state' => 'Unity', 'county' => 'Panyijar'],
            ['name' => 'Abiemnom', 'state' => 'Unity', 'county' => 'Abiemnom'],
            ['name' => 'Nhialdiu', 'state' => 'Unity', 'county' => 'Nhialdiu'],

            ['name' => 'Malakal Town', 'state' => 'Upper Nile', 'county' => 'Malakal'],
            ['name' => 'Malakal West', 'state' => 'Upper Nile', 'county' => 'Malakal'],
            ['name' => 'Renk', 'state' => 'Upper Nile', 'county' => 'Renk'],
            ['name' => 'Renk East', 'state' => 'Upper Nile', 'county' => 'Renk'],
            ['name' => 'Baliet', 'state' => 'Upper Nile', 'county' => 'Baliet'],
            ['name' => 'Longochuk', 'state' => 'Upper Nile', 'county' => 'Longochuk'],
            ['name' => 'Mudit', 'state' => 'Upper Nile', 'county' => 'Mudit'],
            ['name' => 'Manyo', 'state' => 'Upper Nile', 'county' => 'Manyo'],
            ['name' => 'Nasir', 'state' => 'Upper Nile', 'county' => 'Nasir'],
            ['name' => 'Ulang', 'state' => 'Upper Nile', 'county' => 'Ulang'],
            ['name' => 'Akoka', 'state' => 'Upper Nile', 'county' => 'Akoka'],
            ['name' => 'Panyikang', 'state' => 'Upper Nile', 'county' => 'Panyikang'],
        ];

        foreach ($constituencies as $c) {
            DB::table('nec_constituencies')->updateOrInsert(
                ['name' => $c['name']],
                ['name' => $c['name'], 'code' => \Illuminate\Support\Str::slug($c['name']), 'state' => $c['state'], 'county' => $c['county'], 'status' => 'active']
            );
        }

        $pollingStations = [
            ['name' => 'Juba Primary School', 'constituency' => 'Juba City', 'state' => 'Central Equatoria', 'county' => 'Juba', 'lat' => 4.8594, 'lng' => 31.5713],
            ['name' => 'Kator Community Hall', 'constituency' => 'Kator', 'state' => 'Central Equatoria', 'county' => 'Juba', 'lat' => 4.8466, 'lng' => 31.5804],
            ['name' => 'Kajo-Keji Cathedral', 'constituency' => 'Kajo-Keji', 'state' => 'Central Equatoria', 'county' => 'Kajo-Keji', 'lat' => 3.8764, 'lng' => 30.6086],
            ['name' => 'Yei Trading Center', 'constituency' => 'Yei Town', 'state' => 'Central Equatoria', 'county' => 'Yei', 'lat' => 3.5703, 'lng' => 30.6853],
            ['name' => 'Terekeka Primary', 'constituency' => 'Terekeka', 'state' => 'Central Equatoria', 'county' => 'Terekeka', 'lat' => 3.5852, 'lng' => 30.9273],
            ['name' => 'Bor South School', 'constituency' => 'Bor South', 'state' => 'Jonglei', 'county' => 'Bor', 'lat' => 6.2088, 'lng' => 31.5586],
            ['name' => 'Duk Community Center', 'constituency' => 'Duk', 'state' => 'Jonglei', 'county' => 'Duk', 'lat' => 6.6525, 'lng' => 32.3356],
            ['name' => 'Malakal Town Hall', 'constituency' => 'Malakal Town', 'state' => 'Upper Nile', 'county' => 'Malakal', 'lat' => 9.5334, 'lng' => 31.6605],
            ['name' => 'Renk Stadium', 'constituency' => 'Renk', 'state' => 'Upper Nile', 'county' => 'Renk', 'lat' => 11.7704, 'lng' => 32.5992],
            ['name' => 'Wau Grand Mosque', 'constituency' => 'Wau Centre', 'state' => 'Western Bahr el Ghazal', 'county' => 'Wau', 'lat' => 7.7023, 'lng' => 28.0006],
            ['name' => 'Aweil Centre School', 'constituency' => 'Aweil Centre', 'state' => 'Northern Bahr el Ghazal', 'county' => 'Aweil Centre', 'lat' => 8.7679, 'lng' => 27.4005],
            ['name' => 'Kuajok Market', 'constituency' => 'Kuajok', 'state' => 'Warrap', 'county' => 'Kuajok', 'lat' => 7.9823, 'lng' => 28.3120],
            ['name' => 'Rumbek Primary', 'constituency' => 'Rumbek Centre', 'state' => 'Lakes', 'county' => 'Rumbek Centre', 'lat' => 6.8039, 'lng' => 29.6821],
            ['name' => 'Torit Cathedral', 'constituency' => 'Torit Town', 'state' => 'Eastern Equatoria', 'county' => 'Torit', 'lat' => 4.4093, 'lng' => 32.5689],
            ['name' => 'Yambio Town Hall', 'constituency' => 'Yambio Town', 'state' => 'Western Equatoria', 'county' => 'Yambio', 'lat' => 4.5697, 'lng' => 28.3934],
            ['name' => 'Bentiu IDP Camp', 'constituency' => 'Bentiu Town', 'state' => 'Unity', 'county' => 'Bentiu', 'lat' => 7.7459, 'lng' => 29.8378],
            ['name' => 'Maridi School', 'constituency' => 'Maridi', 'state' => 'Western Equatoria', 'county' => 'Maridi', 'lat' => 4.9157, 'lng' => 29.4611],
            ['name' => 'Nasir Payam', 'constituency' => 'Nasir', 'state' => 'Upper Nile', 'county' => 'Nasir', 'lat' => 9.6756, 'lng' => 33.0489],
            ['name' => 'Mayom Community Hall', 'constituency' => 'Mayom', 'state' => 'Unity', 'county' => 'Mayom', 'lat' => 7.3700, 'lng' => 29.0700],
            ['name' => 'Akobo School', 'constituency' => 'Akobo', 'state' => 'Jonglei', 'county' => 'Akobo', 'lat' => 5.7800, 'lng' => 33.1300],
            ['name' => 'Lobonok Center', 'constituency' => 'Lobonok', 'state' => 'Central Equatoria', 'county' => 'Lobonok', 'lat' => 4.2300, 'lng' => 31.2900],
            ['name' => 'Magwi Primary', 'constituency' => 'Magwi', 'state' => 'Eastern Equatoria', 'county' => 'Magwi', 'lat' => 3.7731, 'lng' => 32.2652],
            ['name' => 'Jur River Center', 'constituency' => 'Jur River', 'state' => 'Western Bahr el Ghazal', 'county' => 'Jur River', 'lat' => 7.4000, 'lng' => 27.8700],
            ['name' => 'Kapoeta Center', 'constituency' => 'Kapoeta East', 'state' => 'Eastern Equatoria', 'county' => 'Kapoeta', 'lat' => 4.0056, 'lng' => 33.5833],
            ['name' => 'Nzara Town Hall', 'constituency' => 'Nzara', 'state' => 'Western Equatoria', 'county' => 'Nzara', 'lat' => 4.5344, 'lng' => 28.2736],
        ];

        $voterCounts = [
            'Juba Primary School' => 1850, 'Kator Community Hall' => 1420, 'Kajo-Keji Cathedral' => 980,
            'Yei Trading Center' => 1250, 'Terekeka Primary' => 640, 'Bor South School' => 1560,
            'Duk Community Center' => 720, 'Malakal Town Hall' => 1780, 'Renk Stadium' => 890,
            'Wau Grand Mosque' => 1320, 'Aweil Centre School' => 1150, 'Kuajok Market' => 610,
            'Rumbek Primary' => 1040, 'Torit Cathedral' => 1230, 'Yambio Town Hall' => 1100,
            'Bentiu IDP Camp' => 970, 'Maridi School' => 690, 'Nasir Payam' => 760,
            'Mayom Community Hall' => 540, 'Akobo School' => 830, 'Lobonok Center' => 470,
            'Magwi Primary' => 880, 'Jur River Center' => 560, 'Kapoeta Center' => 990,
            'Nzara Town Hall' => 720,
        ];

        foreach ($pollingStations as $ps) {
            do {
                $code = 'PS' . strtoupper(substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 6));
            } while (DB::table('nec_polling_stations')->where('code', $code)->exists());

            DB::table('nec_polling_stations')->updateOrInsert(
                ['name' => $ps['name']],
                [
                    'name' => $ps['name'],
                    'code' => $code,
                    'constituency' => $ps['constituency'],
                    'state' => $ps['state'],
                    'county' => $ps['county'],
                    'latitude' => $ps['lat'],
                    'longitude' => $ps['lng'],
                    'registered_voters' => $voterCounts[$ps['name']] ?? 0,
                    'status' => 'active',
                ]
            );
        }

        $payams = [
            // Central Equatoria - Juba County (id 1)
            ['name' => 'Juba Central Payam', 'county' => 'Juba County'],
            ['name' => 'Kator Payam', 'county' => 'Juba County'],
            ['name' => 'Gudele Payam', 'county' => 'Juba County'],
            ['name' => 'Kondokoro Payam', 'county' => 'Juba County'],
            ['name' => 'Lobur Payam', 'county' => 'Juba County'],
            // Kajo Keji County
            ['name' => 'Kajo-Keji Payam', 'county' => 'Kajo Keji County'],
            ['name' => 'Nyamurushia Payam', 'county' => 'Kajo Keji County'],
            ['name' => 'Lilimo Payam', 'county' => 'Kajo Keji County'],
            // Yei River County
            ['name' => 'Yei Payam', 'county' => 'Yei River County'],
            ['name' => 'Keni-Raya Payam', 'county' => 'Yei River County'],
            ['name' => 'Kaya Payam', 'county' => 'Yei River County'],
            // Terekeka County
            ['name' => 'Terekeka Payam', 'county' => 'Terekeka County'],
            ['name' => 'Gondokoro Payam', 'county' => 'Terekeka County'],
            // Budi County
            ['name' => 'Budi Payam', 'county' => 'Budi County'],
            ['name' => 'Chukudum Payam', 'county' => 'Budi County'],

            // Eastern Equatoria - Torit County
            ['name' => 'Torit Payam', 'county' => 'Torit County'],
            ['name' => 'Hai Payam', 'county' => 'Torit County'],
            ['name' => 'Ngarikiri Payam', 'county' => 'Torit County'],
            // Kapoeta East County
            ['name' => 'Kapoeta Payam', 'county' => 'Kapoeta East County'],
            ['name' => 'Nangudi Payam', 'county' => 'Kapoeta East County'],
            // Kapoeta North County
            ['name' => 'Nabunga Payam', 'county' => 'Kapoeta North County'],
            // Kapoeta South County
            ['name' => 'Lokerio Payam', 'county' => 'Kapoeta South County'],
            // Magwi County
            ['name' => 'Magwi Payam', 'county' => 'Magwi County'],
            ['name' => 'Opari Payam', 'county' => 'Magwi County'],
            // Ikotos County
            ['name' => 'Ikotos Payam', 'county' => 'Ikotos County'],
            // Lafon County
            ['name' => 'Lafon Payam', 'county' => 'Lafon County'],
            ['name' => 'Lopa Payam', 'county' => 'Lafon County'],

            // Western Equatoria - Yambio County
            ['name' => 'Yambio Payam', 'county' => 'Yambio County'],
            ['name' => 'Sakiri Payam', 'county' => 'Yambio County'],
            // Nzara County
            ['name' => 'Nzara Payam', 'county' => 'Nzara County'],
            ['name' => 'Kedi Payam', 'county' => 'Nzara County'],
            // Maridi County
            ['name' => 'Maridi Payam', 'county' => 'Maridi County'],
            ['name' => 'Ikwara Payam', 'county' => 'Maridi County'],
            // Mundri East County
            ['name' => 'Mundri Payam', 'county' => 'Mundri East County'],
            ['name' => 'Lui Payam', 'county' => 'Mundri East County'],
            // Mundri West County
            ['name' => 'Kedu Payam', 'county' => 'Mundri West County'],
            // Ezo County
            ['name' => 'Ezo Payam', 'county' => 'Ezo County'],
            ['name' => 'Sakure Payam', 'county' => 'Ezo County'],
            // Tambura County
            ['name' => 'Tambura Payam', 'county' => 'Tambura County'],
            ['name' => 'Naandua Payam', 'county' => 'Tambura County'],
            // Ibba County
            ['name' => 'Ibba Payam', 'county' => 'Ibba County'],

            // Jonglei - Bor South County
            ['name' => 'Bor Payam', 'county' => 'Bor South County'],
            ['name' => 'Baidit Payam', 'county' => 'Bor South County'],
            ['name' => 'Anyidi Payam', 'county' => 'Bor South County'],
            // Duk County
            ['name' => 'Duk Payam', 'county' => 'Duk County'],
            ['name' => 'Padiet Payam', 'county' => 'Duk County'],
            // Fangak County
            ['name' => 'Fangak Payam', 'county' => 'Fangak County'],
            ['name' => 'Phom Payam', 'county' => 'Fangak County'],
            // Ayod County
            ['name' => 'Ayod Payam', 'county' => 'Ayod County'],
            ['name' => 'Domas Payam', 'county' => 'Ayod County'],
            // Akobo County
            ['name' => 'Akobo Payam', 'county' => 'Akobo County'],
            ['name' => 'Wasaak Payam', 'county' => 'Akobo County'],
            // Uror County
            ['name' => 'Uror Payam', 'county' => 'Uror County'],
            // Pibor County (Jonglei)
            ['name' => 'Pibor Payam', 'county' => 'Pibor County'],
            ['name' => 'Lekwongole Payam', 'county' => 'Pibor County'],
            // Twic East County
            ['name' => 'Twic East Payam', 'county' => 'Twic East County'],
            ['name' => 'Pading Payam', 'county' => 'Twic East County'],
            // Nyirol County
            ['name' => 'Nyirol Payam', 'county' => 'Nyirol County'],

            // Unity - Guit County
            ['name' => 'Guit Payam', 'county' => 'Guit County'],
            ['name' => 'Kedok Payam', 'county' => 'Guit County'],
            // Koch County
            ['name' => 'Koch Payam', 'county' => 'Koch County'],
            // Leer County
            ['name' => 'Leer Town Payam', 'county' => 'Leer County'],
            ['name' => 'Thar Jath Payam', 'county' => 'Leer County'],
            // Mayendit County
            ['name' => 'Mayiandit Payam', 'county' => 'Mayendit County'],
            // Mayom County
            ['name' => 'Mayom Payam', 'county' => 'Mayom County'],
            ['name' => 'Kuel Payam', 'county' => 'Mayom County'],
            // Panyijiar County
            ['name' => 'Panyijar Payam', 'county' => 'Panyijiar County'],
            // Abiemnhom County
            ['name' => 'Abiemnhom Payam', 'county' => 'Abiemnhom County'],
            // Rubkona County
            ['name' => 'Rubkona Payam', 'county' => 'Rubkona County'],
            ['name' => 'Dar Payam', 'county' => 'Rubkona County'],
            // Pariang County
            ['name' => 'Pariang Payam', 'county' => 'Pariang County'],

            // Upper Nile - Malakal County
            ['name' => 'Malakal Payam', 'county' => 'Malakal County'],
            ['name' => 'Khor Flus Payam', 'county' => 'Malakal County'],
            // Renk County
            ['name' => 'Renk Payam', 'county' => 'Renk County'],
            ['name' => 'Doleib Hill Payam', 'county' => 'Renk County'],
            ['name' => 'Al-Sabon Payam', 'county' => 'Renk County'],
            // Baliet County
            ['name' => 'Baliet Payam', 'county' => 'Baliet County'],
            // Longochuk County
            ['name' => 'Longochuk Payam', 'county' => 'Longochuk County'],
            // Maban County
            ['name' => 'Maban Payam', 'county' => 'Maban County'],
            // Manyo County
            ['name' => 'Manyo Payam', 'county' => 'Manyo County'],
            // Nasir County
            ['name' => 'Nasir Payam', 'county' => 'Nasir County'],
            ['name' => 'Ganthuong Payam', 'county' => 'Nasir County'],
            // Ulang County
            ['name' => 'Ulang Payam', 'county' => 'Ulang County'],
            // Maiwut County
            ['name' => 'Maiwut Payam', 'county' => 'Maiwut County'],
            // Melut County
            ['name' => 'Melut Payam', 'county' => 'Melut County'],
            // Fashoda County
            ['name' => 'Fashoda Payam', 'county' => 'Fashoda County'],
            // Panyikang County
            ['name' => 'Panyikang Payam', 'county' => 'Panyikang County'],

            // Northern Bahr el Ghazal - Aweil Center County
            ['name' => 'Aweil Central Payam', 'county' => 'Aweil Center County'],
            ['name' => 'Majak Payam', 'county' => 'Aweil Center County'],
            // Aweil East County
            ['name' => 'Kol Payam', 'county' => 'Aweil East County'],
            ['name' => 'Barmayen Payam', 'county' => 'Aweil East County'],
            // Aweil North County
            ['name' => 'Uwayal Payam', 'county' => 'Aweil North County'],
            ['name' => 'Mior Payam', 'county' => 'Aweil North County'],
            // Aweil South County
            ['name' => 'Gum-Bak Payam', 'county' => 'Aweil South County'],
            ['name' => 'Madhol Payam', 'county' => 'Aweil South County'],
            // Aweil West County
            ['name' => 'Ku-Liek Payam', 'county' => 'Aweil West County'],
            ['name' => 'Kur-Mei Payam', 'county' => 'Aweil West County'],

            // Warrap - Gogrial East County
            ['name' => 'Gogrial East Payam', 'county' => 'Gogrial East County'],
            // Gogrial West County
            ['name' => 'Gogrial West Payam', 'county' => 'Gogrial West County'],
            // Tonj East County
            ['name' => 'Tonj Payam', 'county' => 'Tonj East County'],
            // Tonj North County
            ['name' => 'Tonj North Payam', 'county' => 'Tonj North County'],
            // Tonj South County
            ['name' => 'Tonj South Payam', 'county' => 'Tonj South County'],
            // Twic County
            ['name' => 'Twic Payam', 'county' => 'Twic County'],
            ['name' => 'Kuany Payam', 'county' => 'Twic County'],

            // Western Bahr el Ghazal - Wau County
            ['name' => 'Wau Payam', 'county' => 'Wau County'],
            ['name' => 'Mukaya Payam', 'county' => 'Wau County'],
            // Jur River County
            ['name' => 'Barm Payam', 'county' => 'Jur River County'],
            ['name' => 'Bussere Payam', 'county' => 'Jur River County'],
            // Raga County
            ['name' => 'Raga Payam', 'county' => 'Raga County'],

            // Lakes - Rumbek Centre County
            ['name' => 'Rumbek Central Payam', 'county' => 'Rumbek Centre County'],
            ['name' => 'Mapuordit Payam', 'county' => 'Rumbek Centre County'],
            // Rumbek East County
            ['name' => 'Dengpayam Payam', 'county' => 'Rumbek East County'],
            // Rumbek North County
            ['name' => 'Bahr-El-Jebel Payam', 'county' => 'Rumbek North County'],
            // Awerial County
            ['name' => 'Awerial Payam', 'county' => 'Awerial County'],
            // Cueibet County
            ['name' => 'Cueibet Payam', 'county' => 'Cueibet County'],
            // Yirol East County
            ['name' => 'Yirol Payam', 'county' => 'Yirol East County'],
            // Yirol West County
            ['name' => 'Dongol Payam', 'county' => 'Yirol West County'],
            // Wulu County
            ['name' => 'Wulu Payam', 'county' => 'Wulu County'],
        ];

        $bomas = [
            // Juba County
            ['name' => 'Juba Central Boma', 'payam' => 'Juba Central Payam'],
            ['name' => 'Hai Juba Boma', 'payam' => 'Juba Central Payam'],
            ['name' => 'Kator Central Boma', 'payam' => 'Kator Payam'],
            ['name' => 'Kator South Boma', 'payam' => 'Kator Payam'],
            ['name' => 'Gudele One Boma', 'payam' => 'Gudele Payam'],
            ['name' => 'Gudele Two Boma', 'payam' => 'Gudele Payam'],
            ['name' => 'Kondokoro Boma', 'payam' => 'Kondokoro Payam'],
            ['name' => 'Lobur Boma', 'payam' => 'Lobur Payam'],
            // Yei River
            ['name' => 'Yei Central Boma', 'payam' => 'Yei Payam'],
            ['name' => 'Yei East Boma', 'payam' => 'Yei Payam'],
            ['name' => 'Keni-Raya Boma', 'payam' => 'Keni-Raya Payam'],
            ['name' => 'Kaya Central Boma', 'payam' => 'Kaya Payam'],
            // Kajo Keji
            ['name' => 'Kajo-Keji Central Boma', 'payam' => 'Kajo-Keji Payam'],
            ['name' => 'Nyamurushia Boma', 'payam' => 'Nyamurushia Payam'],
            // Bor South
            ['name' => 'Bor Central Boma', 'payam' => 'Bor Payam'],
            ['name' => 'Bor South Boma', 'payam' => 'Bor Payam'],
            ['name' => 'Baidit Boma', 'payam' => 'Baidit Payam'],
            ['name' => 'Anyidi Boma', 'payam' => 'Anyidi Payam'],
            // Renk
            ['name' => 'Renk Central Boma', 'payam' => 'Renk Payam'],
            ['name' => 'Renk South Boma', 'payam' => 'Renk Payam'],
            ['name' => 'Doleib Hill Boma', 'payam' => 'Doleib Hill Payam'],
            ['name' => 'Al-Sabon Boma', 'payam' => 'Al-Sabon Payam'],
            // Malakal
            ['name' => 'Malakal Central Boma', 'payam' => 'Malakal Payam'],
            ['name' => 'Malakal West Boma', 'payam' => 'Malakal Payam'],
            ['name' => 'Khor Flus Boma', 'payam' => 'Khor Flus Payam'],
            // Bentiu - Unity
            ['name' => 'Rubkona Central Boma', 'payam' => 'Rubkona Payam'],
            // Wau
            ['name' => 'Wau Central Boma', 'payam' => 'Wau Payam'],
            ['name' => 'Mukaya Boma', 'payam' => 'Mukaya Payam'],
            // Aweil
            ['name' => 'Aweil Central Boma', 'payam' => 'Aweil Central Payam'],
            ['name' => 'Aweil Town Boma', 'payam' => 'Aweil Central Payam'],
            ['name' => 'Majak Boma', 'payam' => 'Majak Payam'],
            // Torit
            ['name' => 'Torit Central Boma', 'payam' => 'Torit Payam'],
            ['name' => 'Hai Torit Boma', 'payam' => 'Hai Payam'],
            // Yambio
            ['name' => 'Yambio Central Boma', 'payam' => 'Yambio Payam'],
            ['name' => 'Sakiri Boma', 'payam' => 'Sakiri Payam'],
            // Nasir
            ['name' => 'Nasir Central Boma', 'payam' => 'Nasir Payam'],
            ['name' => 'Ganthuong Boma', 'payam' => 'Ganthuong Payam'],
            // Rumbek
            ['name' => 'Rumbek Central Boma', 'payam' => 'Rumbek Central Payam'],
            ['name' => 'Mapuordit Boma', 'payam' => 'Mapuordit Payam'],
            // Maridi
            ['name' => 'Maridi Central Boma', 'payam' => 'Maridi Payam'],
            // Magwi
            ['name' => 'Magwi Central Boma', 'payam' => 'Magwi Payam'],
            // Leer
            ['name' => 'Leer Central Boma', 'payam' => 'Leer Town Payam'],
            ['name' => 'Thar Jath Boma', 'payam' => 'Thar Jath Payam'],
            // Fangak
            ['name' => 'Fangak Central Boma', 'payam' => 'Fangak Payam'],
            // Akobo
            ['name' => 'Akobo Central Boma', 'payam' => 'Akobo Payam'],
            ['name' => 'Wasaak Boma', 'payam' => 'Wasaak Payam'],
            // Tambura
            ['name' => 'Tambura Central Boma', 'payam' => 'Tambura Payam'],
            // Kuajok area
            ['name' => 'Gogrial East Boma', 'payam' => 'Gogrial East Payam'],
            // Kapoeta
            ['name' => 'Kapoeta Central Boma', 'payam' => 'Kapoeta Payam'],
        ];

        foreach ($payams as $p) {
            $countyId = DB::table('nec_counties')->where('name', $p['county'])->value('id');
            if ($countyId) {
                DB::table('nec_payams')->updateOrInsert(
                    ['name' => $p['name'], 'county_id' => $countyId],
                    ['name' => $p['name'], 'county_id' => $countyId, 'status' => 'active']
                );
            }
        }

        foreach ($bomas as $b) {
            $payamRow = DB::table('nec_payams')->where('name', $b['payam'])->first();
            if ($payamRow) {
                DB::table('nec_bomas')->updateOrInsert(
                    ['name' => $b['name'], 'payam_id' => $payamRow->id],
                    ['name' => $b['name'], 'payam_id' => $payamRow->id, 'status' => 'active']
                );
            }
        }

        $this->command->info('GeographicSeeder: Regions: ' . DB::table('nec_regions')->count());
        $this->command->info('States: ' . DB::table('nec_states')->count());
        $this->command->info('Counties: ' . DB::table('nec_counties')->count());
        $this->command->info('Constituencies: ' . DB::table('nec_constituencies')->count());
        $this->command->info('Payams: ' . DB::table('nec_payams')->count());
        $this->command->info('Bomas: ' . DB::table('nec_bomas')->count());
        $this->command->info('Polling Stations: ' . DB::table('nec_polling_stations')->count());
    }
}
