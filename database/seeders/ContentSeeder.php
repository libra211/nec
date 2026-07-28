<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $this->seedNews($now);
        $this->seedAnnouncements($now);
        $this->seedFaqs($now);
        $this->seedSpeeches($now);
        $this->seedEducationMaterials($now);
        $this->seedGallery($now);

        $this->command->info('News: ' . DB::table('nec_news')->count());
        $this->command->info('Announcements: ' . DB::table('nec_announcements')->count());
        $this->command->info('FAQs: ' . DB::table('nec_faq')->count());
        $this->command->info('Speeches: ' . DB::table('nec_speeches')->count());
        $this->command->info('Education: ' . DB::table('nec_education_materials')->count());
        $this->command->info('Gallery: ' . DB::table('nec_gallery')->count());
    }

    private function seedNews($now): void
    {
        $articles = [
            ['title' => 'NEC Announces Timeline for 2026 General Elections', 'slug' => 'nec-announces-timeline-2026-elections', 'excerpt' => 'The National Electoral Commission has officially released the comprehensive timeline for the upcoming 2026 general elections across South Sudan.', 'content' => '<p>The National Electoral Commission (NEC) of South Sudan has officially released the comprehensive timeline for the upcoming 2026 general elections. The electoral process is set to begin with voter registration in early 2026, followed by candidate nomination and the election period.</p><p>Chairperson Dr. Abel Alier Kuai stated that the commission is fully prepared to conduct free, fair, and transparent elections across all 10 states and 3 administrative areas.</p>', 'category' => 'press_release', 'author' => 'NEC Communications', 'status' => 'published'],
            ['title' => 'Voter Registration Campaign Launched in Central Equatoria', 'slug' => 'voter-registration-campaign-central-equatoria', 'excerpt' => 'NEC has launched an intensive voter registration campaign targeting all 12 constituencies in Central Equatoria State.', 'content' => '<p>The National Electoral Commission has launched a comprehensive voter registration campaign across Central Equatoria State, targeting all 12 constituencies including Juba City, Kator, Kajo-Keji, and Yei.</p><p>Registration centers have been established at schools, community halls, and government buildings throughout the state.</p>', 'category' => 'news', 'author' => 'NEC Communications', 'status' => 'published'],
            ['title' => 'NEC Deploys Biometric Registration Equipment Across All States', 'slug' => 'nec-deploys-biometric-equipment', 'excerpt' => 'Advanced biometric voter registration equipment has been deployed to all 10 states to ensure accurate voter identification.', 'content' => '<p>The NEC has completed the deployment of state-of-the-art biometric voter registration equipment to all 10 states. The equipment includes fingerprint scanners and facial recognition cameras.</p>', 'category' => 'news', 'author' => 'NEC IT Department', 'status' => 'published'],
            ['title' => 'International Election Observers Invited to Monitor 2026 Elections', 'slug' => 'international-observers-invited-2026', 'excerpt' => 'NEC invites international and domestic observer organizations to apply for accreditation to monitor the 2026 elections.', 'content' => '<p>The National Electoral Commission invites all international and domestic observer organizations to apply for accreditation to monitor the 2026 general elections.</p>', 'category' => 'press_release', 'author' => 'NEC Communications', 'status' => 'published'],
            ['title' => 'NEC Conducts Stakeholder Consultations in Greater Equatoria', 'slug' => 'stakeholder-consultations-greater-equatoria', 'excerpt' => 'NEC leadership held extensive consultations with political parties, civil society organizations, and community leaders in Greater Equatoria.', 'content' => '<p>NEC leadership conducted a series of stakeholder consultations across Greater Equatoria, engaging with political parties, civil society organizations, women\'s groups, and youth organizations.</p>', 'category' => 'news', 'author' => 'NEC Communications', 'status' => 'published'],
            ['title' => 'Voter Transfer Process Simplified for 2026 Elections', 'slug' => 'voter-transfer-process-simplified', 'excerpt' => 'NEC announces streamlined voter transfer process allowing citizens to update their constituency registration.', 'content' => '<p>NEC has simplified the voter transfer process. Registered voters who have relocated can now apply for constituency transfer through the NEC online portal or at any local NEC office.</p>', 'category' => 'news', 'author' => 'NEC Communications', 'status' => 'published'],
            ['title' => 'NEC Opens Nominations for Presidential Candidates', 'slug' => 'nec-opens-presidential-nominations', 'excerpt' => 'The nomination period for presidential candidates in the 2026 general elections is officially open.', 'content' => '<p>The National Electoral Commission announces the opening of the nomination period for presidential candidates. Eligible candidates must submit their nominations to the NEC headquarters in Juba.</p>', 'category' => 'press_release', 'author' => 'NEC Communications', 'status' => 'published'],
            ['title' => 'Peaceful Elections Key to South Sudan\'s Stability, Says NEC', 'slug' => 'peaceful-elections-key-stability', 'excerpt' => 'NEC emphasizes the importance of peaceful and orderly elections as the cornerstone of democratic governance.', 'content' => '<p>The National Electoral Commission has emphasized the critical importance of conducting peaceful and orderly elections as the foundation of democratic governance in South Sudan.</p>', 'category' => 'news', 'author' => 'NEC Communications', 'status' => 'published'],
            ['title' => 'NEC Establishes 25 Polling Stations Across 5 States', 'slug' => 'nec-establishes-polling-stations', 'excerpt' => 'New polling stations established to increase accessibility for voters in remote areas.', 'content' => '<p>NEC has established 25 new polling stations across five states to increase voter accessibility, particularly in remote and underserved areas.</p>', 'category' => 'news', 'author' => 'NEC Operations', 'status' => 'published'],
            ['title' => 'Training Program for Polling Station Officers Launched', 'slug' => 'training-polling-station-officers', 'excerpt' => 'Comprehensive training program launched for all polling station officers.', 'content' => '<p>NEC has launched a comprehensive training program for polling station officers who will be deployed across all constituencies during the 2026 elections.</p>', 'category' => 'news', 'author' => 'NEC Training Unit', 'status' => 'published'],
            ['title' => 'NEC Launches Public Awareness Campaign on Electoral Process', 'slug' => 'nec-public-awareness-campaign', 'excerpt' => 'Massive public awareness campaign launched to educate citizens on the electoral process.', 'content' => '<p>NEC has launched a nationwide public awareness campaign to educate citizens about the electoral process. The campaign utilizes radio broadcasts in local languages and community meetings.</p>', 'category' => 'news', 'author' => 'NEC Communications', 'status' => 'published'],
            ['title' => 'NEC Chairman Addresses National Press Conference', 'slug' => 'nec-chairman-press-conference', 'excerpt' => 'NEC Chairman Dr. Abel Alier Kuai held a press conference to update the nation on electoral preparations.', 'content' => '<p>NEC Chairman Dr. Abel Alier Kuai held a comprehensive press conference at the NEC headquarters in Juba, providing detailed updates on electoral preparations.</p>', 'category' => 'press_release', 'author' => 'NEC Media', 'status' => 'published'],
            ['title' => 'Women\'s Participation in Elections Encouraged by NEC', 'slug' => 'womens-participation-elections', 'excerpt' => 'NEC launches initiative to increase women\'s participation as voters, candidates, and election officials.', 'content' => '<p>NEC has launched a targeted initiative to increase women\'s participation in the 2026 elections as voters, candidates, and election officials.</p>', 'category' => 'news', 'author' => 'NEC Gender Unit', 'status' => 'published'],
            ['title' => 'NEC Partners with UNDP for Election Support', 'slug' => 'nec-partners-undp-election-support', 'excerpt' => 'NEC signs cooperation agreement with UNDP to strengthen electoral capacity.', 'content' => '<p>The National Electoral Commission has signed a cooperation agreement with the United Nations Development Programme (UNDP) to strengthen electoral capacity.</p>', 'category' => 'press_release', 'author' => 'NEC Communications', 'status' => 'published'],
            ['title' => 'Electoral Complaints Mechanism Established', 'slug' => 'electoral-complaints-mechanism', 'excerpt' => 'NEC establishes a dedicated complaints mechanism for citizens to report election-related concerns.', 'content' => '<p>NEC has established a dedicated electoral complaints mechanism to ensure citizens can report concerns, violations, and disputes related to the electoral process.</p>', 'category' => 'news', 'author' => 'NEC Legal Affairs', 'status' => 'published'],
        ];

        foreach ($articles as $a) {
            DB::table('nec_news')->updateOrInsert(
                ['slug' => $a['slug']],
                array_merge($a, ['created_at' => $now])
            );
        }
    }

    private function seedAnnouncements($now): void
    {
        $announcements = [
            ['title' => 'Voter Registration Centers Now Open', 'slug' => 'voter-registration-centers-now-open', 'content' => 'All voter registration centers across South Sudan are now officially open. Citizens are encouraged to register or verify their voter registration status at the nearest center.', 'status' => 'published', 'type' => 'general', 'author' => 'NEC Administration', 'created_at' => $now],
            ['title' => 'Holiday Schedule for Electoral Offices', 'slug' => 'holiday-schedule-electoral-offices', 'content' => 'NEC offices will operate on modified schedules during the upcoming public holidays. Emergency services will remain available.', 'status' => 'published', 'type' => 'general', 'author' => 'NEC Administration', 'created_at' => $now],
            ['title' => 'New National ID Required for Voter Registration', 'slug' => 'new-national-id-required', 'content' => 'Starting from next month, all new voter registrations will require a valid South Sudan national identification card.', 'status' => 'published', 'type' => 'important', 'author' => 'NEC Registration', 'created_at' => $now],
            ['title' => 'NEC Website Maintenance Notice', 'slug' => 'nec-website-maintenance', 'content' => 'The NEC website will undergo scheduled maintenance this weekend. Some online services may be temporarily unavailable.', 'status' => 'published', 'type' => 'general', 'author' => 'NEC IT Department', 'created_at' => $now],
            ['title' => 'Candidate Nomination Period Extended', 'slug' => 'candidate-nomination-extended', 'content' => 'NEC has extended the candidate nomination period by 14 days to allow more time for candidates to complete their documentation.', 'status' => 'published', 'type' => 'important', 'author' => 'NEC Elections', 'created_at' => $now],
            ['title' => 'Community Engagement Sessions in Juba', 'slug' => 'community-engagement-juba', 'content' => 'NEC will hold community engagement sessions in Juba this week to address public concerns about the electoral process.', 'status' => 'published', 'type' => 'general', 'author' => 'NEC Outreach', 'created_at' => $now],
            ['title' => 'Polling Station Locations Published', 'slug' => 'polling-station-locations-published', 'content' => 'The complete list of polling station locations for all 102 constituencies has been published.', 'status' => 'published', 'type' => 'important', 'author' => 'NEC Operations', 'created_at' => $now],
            ['title' => 'NEC Job Vacancies Available', 'slug' => 'nec-job-vacancies', 'content' => 'The National Electoral Commission is recruiting temporary staff for the upcoming election cycle.', 'status' => 'published', 'type' => 'general', 'author' => 'NEC HR', 'created_at' => $now],
        ];

        foreach ($announcements as $a) {
            DB::table('nec_announcements')->updateOrInsert(
                ['slug' => $a['slug']],
                $a
            );
        }
    }

    private function seedFaqs($now): void
    {
        $faqs = [
            ['question' => 'Who is eligible to vote in South Sudan?', 'answer' => 'Any South Sudanese citizen aged 18 years or older who is registered as a voter is eligible to vote in national elections.', 'category' => 'eligibility', 'sort_order' => 1, 'status' => 'published'],
            ['question' => 'How do I register to vote?', 'answer' => 'Visit your nearest voter registration center with a valid national ID or passport. Fill out the registration form and provide biometric data.', 'category' => 'registration', 'sort_order' => 2, 'status' => 'published'],
            ['question' => 'Can I transfer my voter registration to a different constituency?', 'answer' => 'Yes, you can apply for a voter transfer if you have relocated. Submit a transfer request through the NEC online portal or at any NEC office.', 'category' => 'transfer', 'sort_order' => 3, 'status' => 'published'],
            ['question' => 'What documents do I need to register?', 'answer' => 'You need a valid South Sudan national ID card, passport, or any government-issued identification document.', 'category' => 'registration', 'sort_order' => 4, 'status' => 'published'],
            ['question' => 'How can I check if I am already registered?', 'answer' => 'You can check your voter registration status through the NEC voter verification portal, by SMS, or by visiting any NEC office.', 'category' => 'verification', 'sort_order' => 5, 'status' => 'published'],
            ['question' => 'What happens if my voter registration card is lost?', 'answer' => 'Report the loss to the nearest NEC office immediately. You can apply for a replacement card by providing identification documents.', 'category' => 'registration', 'sort_order' => 6, 'status' => 'published'],
            ['question' => 'How are polling stations assigned?', 'answer' => 'Polling stations are assigned based on your registered constituency and ward. Your assigned polling station is printed on your voter registration card.', 'category' => 'voting_day', 'sort_order' => 7, 'status' => 'published'],
            ['question' => 'Can persons with disabilities vote?', 'answer' => 'Absolutely. NEC is committed to ensuring accessible voting for all citizens. Polling stations will be equipped with accessible facilities.', 'category' => 'accessibility', 'sort_order' => 8, 'status' => 'published'],
            ['question' => 'What are the qualifications to run as a candidate?', 'answer' => 'Presidential candidates must be at least 40 years old. Parliamentary candidates must be at least 25 years old.', 'category' => 'candidates', 'sort_order' => 9, 'status' => 'published'],
            ['question' => 'How do I report electoral fraud or irregularities?', 'answer' => 'You can report concerns through NEC\'s dedicated complaints mechanism: toll-free hotline, online submission forms, or at designated complaints offices.', 'category' => 'reporting', 'sort_order' => 10, 'status' => 'published'],
            ['question' => 'When will election results be announced?', 'answer' => 'Results are announced progressively from polling station to constituency to state level. NEC aims to announce official results within 14 days.', 'category' => 'results', 'sort_order' => 11, 'status' => 'published'],
            ['question' => 'Can international observers monitor the elections?', 'answer' => 'Yes, accredited international and domestic observers have full access to polling stations, counting centers, and tallying centers.', 'category' => 'observation', 'sort_order' => 12, 'status' => 'published'],
        ];

        foreach ($faqs as $f) {
            DB::table('nec_faq')->insert(array_merge($f, ['created_at' => $now]));
        }
    }

    private function seedSpeeches($now): void
    {
        $speeches = [
            ['title' => 'Opening Address at the Electoral Preparedness Conference', 'speaker' => 'Dr. Abel Alier Kuai', 'content' => 'Distinguished delegates, it is my honor to open this conference on electoral preparedness. As we prepare for the 2026 general elections, NEC is committed to conducting an electoral process that reflects the will of the South Sudanese people.', 'event_name' => 'Electoral Preparedness Conference', 'speech_date' => $now->subDays(45)->format('Y-m-d'), 'status' => 'published'],
            ['title' => 'Statement on International Day of Democracy', 'speaker' => 'Dr. Abel Alier Kuai', 'content' => 'On this International Day of Democracy, we reaffirm NEC\'s commitment to strengthening democratic institutions in South Sudan.', 'event_name' => 'International Day of Democracy', 'speech_date' => $now->subDays(90)->format('Y-m-d'), 'status' => 'published'],
            ['title' => 'Address to National Assembly on Electoral Progress', 'speaker' => 'Prof. James Obong Abyei', 'content' => 'Honorable members of the National Assembly, I am pleased to report on the significant progress NEC has made in preparing for the upcoming elections.', 'event_name' => 'National Assembly Session', 'speech_date' => $now->subDays(60)->format('Y-m-d'), 'status' => 'published'],
            ['title' => 'Closing Remarks at the Stakeholder Forum', 'speaker' => 'Dr. Abel Alier Kuai', 'content' => 'I thank all stakeholders for their valuable contributions to this forum. The feedback received will directly inform our electoral strategies.', 'event_name' => 'Stakeholder Forum', 'speech_date' => $now->subDays(30)->format('Y-m-d'), 'status' => 'published'],
            ['title' => 'Press Conference on Voter Registration Progress', 'speaker' => 'Prof. James Obong Abyei', 'content' => 'Ladies and gentlemen of the press, I am happy to announce that voter registration has exceeded our targets in several states.', 'event_name' => 'Press Conference', 'speech_date' => $now->subDays(15)->format('Y-m-d'), 'status' => 'published'],
            ['title' => 'Keynote at the Youth Electoral Education Summit', 'speaker' => 'Dr. Abel Alier Kuai', 'content' => 'Young people of South Sudan, you are the future of this nation. Your participation in the electoral process is not just a right but a responsibility.', 'event_name' => 'Youth Electoral Education Summit', 'speech_date' => $now->subDays(50)->format('Y-m-d'), 'status' => 'published'],
        ];

        foreach ($speeches as $s) {
            DB::table('nec_speeches')->insert(array_merge($s, ['created_at' => $now]));
        }
    }

    private function seedEducationMaterials($now): void
    {
        $materials = [
            ['title' => 'Understanding Your Right to Vote', 'content_type' => 'document', 'description' => 'A comprehensive guide explaining the constitutional right to vote in South Sudan.', 'target_audience' => 'general', 'language' => 'English', 'status' => 'published'],
            ['title' => 'Step-by-Step Voter Registration Guide', 'content_type' => 'document', 'description' => 'Detailed instructions on how to register as a voter, what documents to bring.', 'target_audience' => 'general', 'language' => 'English', 'status' => 'published'],
            ['title' => 'How to Vote on Election Day', 'content_type' => 'document', 'description' => 'Step-by-step guide for voters explaining the voting process.', 'target_audience' => 'general', 'language' => 'English', 'status' => 'published'],
            ['title' => 'Voter Transfer Process Explained', 'content_type' => 'document', 'description' => 'Complete guide to transferring your voter registration from one constituency to another.', 'target_audience' => 'general', 'language' => 'English', 'status' => 'published'],
            ['title' => 'Understanding Electoral Complaints', 'content_type' => 'infographic', 'description' => 'Visual guide explaining how to file electoral complaints.', 'target_audience' => 'general', 'language' => 'English', 'status' => 'published'],
            ['title' => 'Accessible Voting for Persons with Disabilities', 'content_type' => 'document', 'description' => 'Information about accommodations and support available for voters with disabilities.', 'target_audience' => 'general', 'language' => 'English', 'status' => 'published'],
            ['title' => 'Youth Guide to Participating in Elections', 'content_type' => 'document', 'description' => 'Special guide for young first-time voters.', 'target_audience' => 'youth', 'language' => 'English', 'status' => 'published'],
            ['title' => 'Election Security and Your Role', 'content_type' => 'infographic', 'description' => 'How citizens can help ensure peaceful elections.', 'target_audience' => 'general', 'language' => 'English', 'status' => 'published'],
        ];

        foreach ($materials as $m) {
            DB::table('nec_education_materials')->insert(array_merge($m, ['created_at' => $now]));
        }
    }

    private function seedGallery($now): void
    {
        $items = [
            ['title' => 'NEC Chairman Addressing the Press', 'description' => 'Dr. Abel Alier Kuai addressing the media during a press conference at NEC headquarters in Juba.', 'album' => 'press', 'image_path' => '/images/gallery/nec-chairman-press.jpg', 'status' => 'published'],
            ['title' => 'Voter Registration in Central Equatoria', 'description' => 'NEC officials assisting citizens with voter registration at a center in Juba.', 'album' => 'registration', 'image_path' => '/images/gallery/voter-registration-ce.jpg', 'status' => 'published'],
            ['title' => 'Biometric Equipment Training Session', 'description' => 'Training session for NEC staff on the use of new biometric voter registration equipment.', 'album' => 'training', 'image_path' => '/images/gallery/biometric-training.jpg', 'status' => 'published'],
            ['title' => 'Stakeholder Consultation Meeting', 'description' => 'NEC leadership meeting with political party representatives and civil society organizations.', 'album' => 'meetings', 'image_path' => '/images/gallery/stakeholder-meeting.jpg', 'status' => 'published'],
            ['title' => 'Polling Station Setup in Bor', 'description' => 'NEC team setting up a polling station at Bor South Primary School.', 'album' => 'operations', 'image_path' => '/images/gallery/polling-station-bor.jpg', 'status' => 'published'],
            ['title' => 'Voter Education Campaign', 'description' => 'Community outreach team conducting voter education in Renk, Upper Nile State.', 'album' => 'education', 'image_path' => '/images/gallery/voter-education-renk.jpg', 'status' => 'published'],
            ['title' => 'International Observers Delegation', 'description' => 'International election observation delegation meeting with NEC officials.', 'album' => 'international', 'image_path' => '/images/gallery/intl-observers.jpg', 'status' => 'published'],
            ['title' => 'NEC Warehouse - Equipment Storage', 'description' => 'Storage facility housing election materials and equipment.', 'album' => 'operations', 'image_path' => '/images/gallery/nec-warehouse.jpg', 'status' => 'published'],
            ['title' => 'Youth Electoral Awareness Rally', 'description' => 'Young South Sudanese participating in an electoral awareness rally organized by NEC in Yei.', 'album' => 'education', 'image_path' => '/images/gallery/youth-rally-yei.jpg', 'status' => 'published'],
            ['title' => 'Women in Elections Workshop', 'description' => 'Workshop promoting women\'s participation in the electoral process.', 'album' => 'education', 'image_path' => '/images/gallery/women-workshop.jpg', 'status' => 'published'],
        ];

        foreach ($items as $item) {
            DB::table('nec_gallery')->insert(array_merge($item, ['created_at' => $now]));
        }
    }
}
