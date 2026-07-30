<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ObserverSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        // Seed nec_observer_applications (individual applications from public form)
        $applications = [
            ['first_name' => 'Amina', 'last_name' => 'Ali', 'other_names' => 'Osman', 'gender' => 'female', 'email' => 'amina.ali@au.int', 'phone' => '+254700100200', 'observer_type' => 'international', 'organization_name' => 'African Union Election Observation Mission', 'status' => 'approved', 'created_at' => $now],
            ['first_name' => 'Jean-Pierre', 'last_name' => 'Mbarga', 'other_names' => null, 'gender' => 'male', 'email' => 'jp.mbarga@ecowas.int', 'phone' => '+22890123456', 'observer_type' => 'international', 'organization_name' => 'ECOWAS Election Observation Group', 'status' => 'approved', 'created_at' => $now],
            ['first_name' => 'Sarah', 'last_name' => 'Mitchell', 'other_names' => null, 'gender' => 'female', 'email' => 's.mitchell@eeas.europa.eu', 'phone' => '+32470123456', 'observer_type' => 'international', 'organization_name' => 'European Union Election Observation Mission', 'status' => 'approved', 'created_at' => $now],
            ['first_name' => 'James', 'last_name' => 'Okello', 'other_names' => null, 'gender' => 'male', 'email' => 'j.okello@eac.int', 'phone' => '+256701234567', 'observer_type' => 'international', 'organization_name' => 'East African Community Observer Mission', 'status' => 'approved', 'created_at' => $now],
            ['first_name' => 'Fatima', 'last_name' => 'Hassan', 'other_names' => 'Ambassador', 'gender' => 'female', 'email' => 'f.hassan@igad.int', 'phone' => '+252611234567', 'observer_type' => 'international', 'organization_name' => 'IGAD Election Observation Team', 'status' => 'pending', 'created_at' => $now],
            ['first_name' => 'Peter', 'last_name' => 'Wol', 'other_names' => 'Lam', 'gender' => 'male', 'email' => 'peter.wol@sscc.org', 'phone' => '+211912300001', 'observer_type' => 'domestic', 'organization_name' => 'South Sudan Council of Churches', 'status' => 'approved', 'created_at' => $now],
            ['first_name' => 'Victoria', 'last_name' => 'Adut', 'other_names' => null, 'gender' => 'female', 'email' => 'v.adut@sswco.org', 'phone' => '+211912300002', 'observer_type' => 'domestic', 'organization_name' => "South Sudan Women's Coalition", 'status' => 'approved', 'created_at' => $now],
            ['first_name' => 'Emmanuel', 'last_name' => 'Monyluak', 'other_names' => null, 'gender' => 'male', 'email' => 'e.monyluak@cepo.org', 'phone' => '+211912300003', 'observer_type' => 'domestic', 'organization_name' => 'Community Empowerment for Progress Organization', 'status' => 'approved', 'created_at' => $now],
            ['first_name' => 'Luka', 'last_name' => 'Gaudensio', 'other_names' => 'Bona', 'gender' => 'male', 'email' => 'l.bona@ndi.org', 'phone' => '+12025551234', 'observer_type' => 'international', 'organization_name' => 'National Democratic Institute', 'status' => 'pending', 'created_at' => $now],
            ['first_name' => 'Rachel', 'last_name' => 'Nyaakol', 'other_names' => null, 'gender' => 'female', 'email' => 'r.nyaakol@ssyu.org', 'phone' => '+211912300004', 'observer_type' => 'domestic', 'organization_name' => 'South Sudan Youth Union', 'status' => 'approved', 'created_at' => $now],
            ['first_name' => 'Ahmed', 'last_name' => 'Nur', 'other_names' => 'Ibrahim', 'gender' => 'male', 'email' => 'a.nur@ifes.org', 'phone' => '+17035551234', 'observer_type' => 'international', 'organization_name' => 'International Foundation for Electoral Systems', 'status' => 'pending', 'created_at' => $now],
            ['first_name' => 'Grace', 'last_name' => 'Lopidia', 'other_names' => null, 'gender' => 'female', 'email' => 'g.lopidia@sshrs.org', 'phone' => '+211912300005', 'observer_type' => 'domestic', 'organization_name' => 'South Sudan Human Rights Society', 'status' => 'approved', 'created_at' => $now],
            ['first_name' => 'Festus', 'last_name' => 'Mogoe', 'other_names' => null, 'gender' => 'male', 'email' => 'f.mogoe@eisa.org', 'phone' => '+27112345678', 'observer_type' => 'international', 'organization_name' => 'Electoral Institute for Sustainable Democracy in Africa', 'status' => 'pending', 'created_at' => $now],
            ['first_name' => 'Peter', 'last_name' => 'Garang', 'other_names' => null, 'gender' => 'male', 'email' => 'p.garang@sscch.org', 'phone' => '+211912300006', 'observer_type' => 'domestic', 'organization_name' => 'Sudan Council of Churches', 'status' => 'approved', 'created_at' => $now],
            ['first_name' => 'Hellen', 'last_name' => 'Bakhita', 'other_names' => null, 'gender' => 'female', 'email' => 'h.bakhita@crisisgroup.org', 'phone' => '+3225027200', 'observer_type' => 'international', 'organization_name' => 'International Crisis Group', 'status' => 'pending', 'created_at' => $now],
            ['first_name' => 'Joseph', 'last_name' => 'Okwang', 'other_names' => null, 'gender' => 'male', 'email' => 'j.okwang@ssuju.org', 'phone' => '+211912300007', 'observer_type' => 'domestic', 'organization_name' => 'South Sudan National Journalists Union', 'status' => 'pending', 'created_at' => $now],
            ['first_name' => 'Caroline', 'last_name' => 'Achieng', 'other_names' => null, 'gender' => 'female', 'email' => 'c.achieng@au.int', 'phone' => '+251112345678', 'observer_type' => 'international', 'organization_name' => 'African Union Peace and Security Council', 'status' => 'pending', 'created_at' => $now],
            ['first_name' => 'Deng', 'last_name' => 'Kuol', 'other_names' => 'Bol', 'gender' => 'male', 'email' => 'd.bol@sscsa.org', 'phone' => '+211912300008', 'observer_type' => 'domestic', 'organization_name' => 'South Sudan Civil Society Alliance', 'status' => 'pending', 'created_at' => $now],
            ['first_name' => 'Nyamal', 'last_name' => 'Peter', 'other_names' => null, 'gender' => 'female', 'email' => 'n.peter@ssdn.org', 'phone' => '+211912300009', 'observer_type' => 'domestic', 'organization_name' => 'South Sudanese Diaspora Network', 'status' => 'pending', 'created_at' => $now],
            ['first_name' => 'Thomas', 'last_name' => 'Boni', 'other_names' => null, 'gender' => 'male', 'email' => 't.boni@cartercenter.org', 'phone' => '+14045551234', 'observer_type' => 'international', 'organization_name' => 'Carter Center Election Observation Program', 'status' => 'pending', 'created_at' => $now],
            ['first_name' => 'Joseph', 'last_name' => 'Lomo', 'other_names' => null, 'gender' => 'male', 'email' => 'j.lomo@ssemn.org', 'phone' => '+211912300010', 'observer_type' => 'domestic', 'organization_name' => 'South Sudan Election Monitoring Network', 'status' => 'pending', 'created_at' => $now],
            ['first_name' => 'Priscilla', 'last_name' => 'Joseph', 'other_names' => null, 'gender' => 'female', 'email' => 'p.joseph@sswpn.org', 'phone' => '+211912300011', 'observer_type' => 'domestic', 'organization_name' => "South Sudan Women's Peace Network", 'status' => 'pending', 'created_at' => $now],
            ['first_name' => 'Khamis', 'last_name' => 'Deng', 'other_names' => null, 'gender' => 'male', 'email' => 'k.deng@ssyd.org', 'phone' => '+211912300012', 'observer_type' => 'domestic', 'organization_name' => 'South Sudan Youth for Democracy', 'status' => 'pending', 'created_at' => $now],
        ];

        foreach ($applications as $o) {
            DB::table('nec_observer_applications')->insert($o);
        }

        // Seed nec_observers (for admin observer management page)
        $observers = [
            [
                'email' => 'amina.ali@au.int', 'password' => bcrypt('password'), 'last_name' => 'Ali',
                'other_names' => 'Amina Osman', 'title' => 'Ambassador', 'gender' => 'female',
                'phone' => '+254700100200', 'observer_type' => 'individual', 'category' => 'international',
                'nationality' => 'Kenyan', 'organisation_name' => 'African Union Election Observation Mission',
                'status' => 'accredited', 'accredited_at' => $now, 'created_at' => $now,
            ],
            [
                'email' => 'jp.mbarga@ecowas.int', 'password' => bcrypt('password'), 'last_name' => 'Mbarga',
                'other_names' => 'Jean-Pierre', 'title' => 'Dr.', 'gender' => 'male',
                'phone' => '+22890123456', 'observer_type' => 'individual', 'category' => 'international',
                'nationality' => 'Togolese', 'organisation_name' => 'ECOWAS Election Observation Group',
                'status' => 'accredited', 'accredited_at' => $now, 'created_at' => $now,
            ],
            [
                'email' => 's.mitchell@eeas.europa.eu', 'password' => bcrypt('password'), 'last_name' => 'Mitchell',
                'other_names' => 'Sarah', 'title' => 'Ms.', 'gender' => 'female',
                'phone' => '+32470123456', 'observer_type' => 'individual', 'category' => 'international',
                'nationality' => 'British', 'organisation_name' => 'European Union Election Observation Mission',
                'status' => 'accredited', 'accredited_at' => $now, 'created_at' => $now,
            ],
            [
                'email' => 'j.okello@eac.int', 'password' => bcrypt('password'), 'last_name' => 'Okello',
                'other_names' => 'James', 'title' => 'Prof.', 'gender' => 'male',
                'phone' => '+256701234567', 'observer_type' => 'individual', 'category' => 'international',
                'nationality' => 'Ugandan', 'organisation_name' => 'East African Community Observer Mission',
                'status' => 'accredited', 'accredited_at' => $now, 'created_at' => $now,
            ],
            [
                'email' => 'f.hassan@igad.int', 'password' => bcrypt('password'), 'last_name' => 'Hassan',
                'other_names' => 'Fatima Ambassador', 'title' => 'Ambassador', 'gender' => 'female',
                'phone' => '+252611234567', 'observer_type' => 'individual', 'category' => 'international',
                'nationality' => 'Somali', 'organisation_name' => 'IGAD Election Observation Team',
                'status' => 'verified', 'verified_at' => $now, 'created_at' => $now,
            ],
            [
                'email' => 'peter.wol@sscc.org', 'password' => bcrypt('password'), 'last_name' => 'Wol',
                'other_names' => 'Peter Lam', 'title' => 'Rev.', 'gender' => 'male',
                'phone' => '+211912300001', 'observer_type' => 'individual', 'category' => 'domestic',
                'nationality' => 'South Sudanese', 'organisation_name' => 'South Sudan Council of Churches',
                'status' => 'accredited', 'accredited_at' => $now, 'created_at' => $now,
            ],
            [
                'email' => 'v.adut@sswco.org', 'password' => bcrypt('password'), 'last_name' => 'Adut',
                'other_names' => 'Victoria', 'title' => 'Ms.', 'gender' => 'female',
                'phone' => '+211912300002', 'observer_type' => 'individual', 'category' => 'domestic',
                'nationality' => 'South Sudanese', 'organisation_name' => "South Sudan Women's Coalition",
                'status' => 'accredited', 'accredited_at' => $now, 'created_at' => $now,
            ],
            [
                'email' => 'e.monyluak@cepo.org', 'password' => bcrypt('password'), 'last_name' => 'Monyluak',
                'other_names' => 'Emmanuel', 'title' => 'Mr.', 'gender' => 'male',
                'phone' => '+211912300003', 'observer_type' => 'individual', 'category' => 'domestic',
                'nationality' => 'South Sudanese', 'organisation_name' => 'Community Empowerment for Progress Organization',
                'status' => 'accredited', 'accredited_at' => $now, 'created_at' => $now,
            ],
            [
                'email' => 'l.bona@ndi.org', 'password' => bcrypt('password'), 'last_name' => 'Gaudensio',
                'other_names' => 'Luka Bona', 'title' => 'Mr.', 'gender' => 'male',
                'phone' => '+12025551234', 'observer_type' => 'individual', 'category' => 'international',
                'nationality' => 'American', 'organisation_name' => 'National Democratic Institute',
                'status' => 'verified', 'verified_at' => $now, 'created_at' => $now,
            ],
            [
                'email' => 'r.nyaakol@ssyu.org', 'password' => bcrypt('password'), 'last_name' => 'Nyaakol',
                'other_names' => 'Rachel', 'title' => 'Ms.', 'gender' => 'female',
                'phone' => '+211912300004', 'observer_type' => 'individual', 'category' => 'domestic',
                'nationality' => 'South Sudanese', 'organisation_name' => 'South Sudan Youth Union',
                'status' => 'accredited', 'accredited_at' => $now, 'created_at' => $now,
            ],
            [
                'email' => 'a.nur@ifes.org', 'password' => bcrypt('password'), 'last_name' => 'Nur',
                'other_names' => 'Ahmed Ibrahim', 'title' => 'Dr.', 'gender' => 'male',
                'phone' => '+17035551234', 'observer_type' => 'individual', 'category' => 'international',
                'nationality' => 'Sudanese', 'organisation_name' => 'International Foundation for Electoral Systems',
                'status' => 'pending', 'created_at' => $now,
            ],
            [
                'email' => 'g.lopidia@sshrs.org', 'password' => bcrypt('password'), 'last_name' => 'Lopidia',
                'other_names' => 'Grace', 'title' => 'Ms.', 'gender' => 'female',
                'phone' => '+211912300005', 'observer_type' => 'individual', 'category' => 'domestic',
                'nationality' => 'South Sudanese', 'organisation_name' => 'South Sudan Human Rights Society',
                'status' => 'accredited', 'accredited_at' => $now, 'created_at' => $now,
            ],
            [
                'email' => 'f.mogoe@eisa.org', 'password' => bcrypt('password'), 'last_name' => 'Mogoe',
                'other_names' => 'Festus', 'title' => 'Mr.', 'gender' => 'male',
                'phone' => '+27112345678', 'observer_type' => 'individual', 'category' => 'international',
                'nationality' => 'South African', 'organisation_name' => 'Electoral Institute for Sustainable Democracy in Africa',
                'status' => 'verified', 'verified_at' => $now, 'created_at' => $now,
            ],
            [
                'email' => 'p.garang@sscch.org', 'password' => bcrypt('password'), 'last_name' => 'Garang',
                'other_names' => 'Peter', 'title' => 'Bishop', 'gender' => 'male',
                'phone' => '+211912300006', 'observer_type' => 'individual', 'category' => 'domestic',
                'nationality' => 'South Sudanese', 'organisation_name' => 'Sudan Council of Churches',
                'status' => 'accredited', 'accredited_at' => $now, 'created_at' => $now,
            ],
            [
                'email' => 'h.bakhita@crisisgroup.org', 'password' => bcrypt('password'), 'last_name' => 'Bakhita',
                'other_names' => 'Hellen', 'title' => 'Ms.', 'gender' => 'female',
                'phone' => '+3225027200', 'observer_type' => 'individual', 'category' => 'international',
                'nationality' => 'South Sudanese', 'organisation_name' => 'International Crisis Group',
                'status' => 'pending', 'created_at' => $now,
            ],
            [
                'email' => 'j.okwang@ssuju.org', 'password' => bcrypt('password'), 'last_name' => 'Okwang',
                'other_names' => 'Joseph', 'title' => 'Mr.', 'gender' => 'male',
                'phone' => '+211912300007', 'observer_type' => 'individual', 'category' => 'domestic',
                'nationality' => 'South Sudanese', 'organisation_name' => 'South Sudan National Journalists Union',
                'status' => 'pending', 'created_at' => $now,
            ],
            [
                'email' => 'c.achieng@au.int', 'password' => bcrypt('password'), 'last_name' => 'Achieng',
                'other_names' => 'Caroline', 'title' => 'Dr.', 'gender' => 'female',
                'phone' => '+251112345678', 'observer_type' => 'individual', 'category' => 'international',
                'nationality' => 'Ethiopian', 'organisation_name' => 'African Union Peace and Security Council',
                'status' => 'pending', 'created_at' => $now,
            ],
            [
                'email' => 'd.bol@sscsa.org', 'password' => bcrypt('password'), 'last_name' => 'Kuol',
                'other_names' => 'Deng Bol', 'title' => 'Mr.', 'gender' => 'male',
                'phone' => '+211912300008', 'observer_type' => 'individual', 'category' => 'domestic',
                'nationality' => 'South Sudanese', 'organisation_name' => 'South Sudan Civil Society Alliance',
                'status' => 'pending', 'created_at' => $now,
            ],
            [
                'email' => 'n.peter@ssdn.org', 'password' => bcrypt('password'), 'last_name' => 'Peter',
                'other_names' => 'Nyamal', 'title' => 'Ms.', 'gender' => 'female',
                'phone' => '+211912300009', 'observer_type' => 'individual', 'category' => 'domestic',
                'nationality' => 'South Sudanese', 'organisation_name' => 'South Sudanese Diaspora Network',
                'status' => 'pending', 'created_at' => $now,
            ],
            [
                'email' => 't.boni@cartercenter.org', 'password' => bcrypt('password'), 'last_name' => 'Boni',
                'other_names' => 'Thomas', 'title' => 'Mr.', 'gender' => 'male',
                'phone' => '+14045551234', 'observer_type' => 'individual', 'category' => 'international',
                'nationality' => 'American', 'organisation_name' => 'Carter Center Election Observation Program',
                'status' => 'pending', 'created_at' => $now,
            ],
        ];

        DB::table('nec_observers')->truncate();
        foreach ($observers as $o) {
            DB::table('nec_observers')->insert($o);
        }

        $this->command->info('ObserverSeeder: ObserverApplications: ' . DB::table('nec_observer_applications')->count() . ', Observers: ' . DB::table('nec_observers')->count());
    }
}
