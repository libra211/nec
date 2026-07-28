<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContactSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $contacts = [
            ['name' => 'Michael Deng', 'email' => 'michael.deng@gmail.com', 'phone' => '+211917001001', 'subject' => 'Voter Registration Inquiry', 'message' => 'I would like to know the dates for voter registration in Juba. Where is the nearest registration center?', 'topic' => 'registration', 'status' => 'new', 'created_at' => $now->subDays(1)],
            ['name' => 'Sarah Nyakim', 'email' => 'sarah.nyakim@yahoo.com', 'phone' => '+211917001002', 'subject' => 'Transfer Request Status', 'message' => 'I submitted a voter transfer request two weeks ago. My reference number is TRF-2026-0045.', 'topic' => 'transfer', 'status' => 'new', 'created_at' => $now->subDays(2)],
            ['name' => 'Peter Garang Kuol', 'email' => 'peter.kuol@hotmail.com', 'phone' => '+211917001003', 'subject' => 'Polling Station Location', 'message' => 'I need to know the location of my assigned polling station for the upcoming elections. My voter ID is NEC26M000125.', 'topic' => 'general', 'status' => 'new', 'created_at' => $now->subDays(3)],
            ['name' => 'Grace Atem', 'email' => 'grace.atem@gmail.com', 'phone' => '+211917001004', 'subject' => 'Candidate Nomination Process', 'message' => 'I am interested in running for a seat in the National Legislative Assembly from Bor South constituency.', 'topic' => 'candidates', 'status' => 'new', 'created_at' => $now->subDays(4)],
            ['name' => 'James Taban', 'email' => 'james.taban@outlook.com', 'phone' => '+211917001005', 'subject' => 'Lost Voter Card', 'message' => 'My voter registration card was stolen. How do I get a replacement?', 'topic' => 'registration', 'status' => 'replied', 'created_at' => $now->subDays(5)],
            ['name' => 'Mary Lopidia', 'email' => 'mary.lopidia@gmail.com', 'phone' => '+211917001006', 'subject' => 'Observer Application', 'message' => 'I am writing on behalf of South Sudan Women\'s Coalition. We would like to apply for observer accreditation.', 'topic' => 'observer', 'status' => 'new', 'created_at' => $now->subDays(6)],
            ['name' => 'Daniel Akec', 'email' => 'daniel.akec@yahoo.com', 'phone' => '+211917001007', 'subject' => 'Election Date Confirmation', 'message' => 'Can you please confirm the official date for the 2026 general elections?', 'topic' => 'general', 'status' => 'replied', 'created_at' => $now->subDays(8)],
            ['name' => 'Elizabeth Bol', 'email' => 'elizabeth.bol@gmail.com', 'phone' => '+211917001008', 'subject' => 'Accessibility at Polling Stations', 'message' => 'I am a person with a wheelchair disability. Will the polling stations be accessible?', 'topic' => 'accessibility', 'status' => 'new', 'created_at' => $now->subDays(10)],
            ['name' => 'Martin Kuol', 'email' => 'martin.kuol@hotmail.com', 'phone' => '+211917001009', 'subject' => 'Volunteer Registration Officer', 'message' => 'I would like to volunteer as a registration officer in my area. I am based in Rumbek Centre.', 'topic' => 'employment', 'status' => 'new', 'created_at' => $now->subDays(12)],
            ['name' => 'Ruth Lual', 'email' => 'ruth.lual@gmail.com', 'phone' => '+211917001010', 'subject' => 'Media Accreditation Request', 'message' => 'I am a journalist with South Sudan Broadcasting Corporation. I would like to request media accreditation.', 'topic' => 'media', 'status' => 'new', 'created_at' => $now->subDays(14)],
            ['name' => 'Isaac Deng', 'email' => 'isaac.deng@gmail.com', 'phone' => '+211917001011', 'subject' => 'Voter Registration Error', 'message' => 'My name was misspelled during registration. It shows "Issac" instead of "Isaac".', 'topic' => 'registration', 'status' => 'replied', 'created_at' => $now->subDays(16)],
            ['name' => 'Nyaluak Maker', 'email' => 'nyaluak.maker@yahoo.com', 'phone' => '+211917001012', 'subject' => 'Youth Voter Education', 'message' => 'Our youth organization would like NEC to conduct a voter education session at our community center in Malakal.', 'topic' => 'education', 'status' => 'new', 'created_at' => $now->subDays(18)],
            ['name' => 'Stephen Wani', 'email' => 'stephen.wani@hotmail.com', 'phone' => '+211917001013', 'subject' => 'Complaint About Campaign', 'message' => 'I want to report a political party that is campaigning near a polling station.', 'topic' => 'complaint', 'status' => 'new', 'created_at' => $now->subDays(20)],
            ['name' => 'Florence Athian', 'email' => 'florence.athian@gmail.com', 'phone' => '+211917001014', 'subject' => 'Duplicate Registration', 'message' => 'I discovered that my mother has been registered twice under different voter IDs.', 'topic' => 'registration', 'status' => 'new', 'created_at' => $now->subDays(22)],
            ['name' => 'Victor Atem', 'email' => 'victor.atem@outlook.com', 'phone' => '+211917001015', 'subject' => 'Polling Station Assignment', 'message' => 'I recently moved from Yei to Juba. How do I update my polling station assignment?', 'topic' => 'transfer', 'status' => 'replied', 'created_at' => $now->subDays(25)],
        ];

        foreach ($contacts as $c) {
            DB::table('nec_contacts')->insert($c);
        }

        $this->command->info('ContactSeeder: Contacts: ' . DB::table('nec_contacts')->count());
    }
}
