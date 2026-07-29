<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PopulateAllSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $this->seedIfEmpty('nec_voter_accounts', fn() => $this->seedVoterAccounts($now));
        $this->seedIfEmpty('nec_voter_transfers', fn() => $this->seedVoterTransfers($now));
        $this->seedIfEmpty('nec_categories', fn() => $this->seedCategories($now));
        $this->seedIfEmpty('nec_tags', fn() => $this->seedTags($now));
        $this->seedIfEmpty('nec_comments', fn() => $this->seedComments($now));
        $this->seedIfEmpty('nec_cms_pages', fn() => $this->seedCmsPages($now));
        $this->seedIfEmpty('nec_menus', fn() => $this->seedMenus($now));
        $this->seedIfEmpty('nec_widgets', fn() => $this->seedWidgets($now));
        $this->seedIfEmpty('nec_subscribers', fn() => $this->seedSubscribers($now));
        $this->seedIfEmpty('nec_api_keys', fn() => $this->seedApiKeys($now));
        $this->seedIfEmpty('nec_assets', fn() => $this->seedAssets($now));
        $this->seedIfEmpty('nec_ballots', fn() => $this->seedBallots($now));
        $this->seedIfEmpty('nec_results', fn() => $this->seedResults($now));
        $this->seedIfEmpty('nec_candidate_results', fn() => $this->seedCandidateResults($now));
        $this->seedIfEmpty('nec_nominations', fn() => $this->seedNominations($now));
        $this->seedIfEmpty('nec_election_petitions', fn() => $this->seedElectionPetitions($now));
        $this->seedIfEmpty('nec_polling_staff', fn() => $this->seedPollingStaff($now));
        $this->seedIfEmpty('nec_complaints', fn() => $this->seedComplaints($now));
        $this->seedIfEmpty('nec_media', fn() => $this->seedMedia($now));
        $this->seedIfEmpty('nec_reports', fn() => $this->seedReports($now));
        $this->seedIfEmpty('nec_downloads', fn() => $this->seedDownloads($now));
        $this->seedIfEmpty('nec_download_stats', fn() => $this->seedDownloadStats($now));
        $this->seedIfEmpty('nec_observers', fn() => $this->seedObservers($now));
        $this->seedIfEmpty('nec_security_logs', fn() => $this->seedSecurityLogs($now));
        $this->seedIfEmpty('nec_sequences', fn() => $this->seedSequences($now));
        $this->seedIfEmpty('nec_events', fn() => $this->seedEvents($now));
        $this->seedIfEmpty('login_logs', fn() => $this->seedLoginLogs($now));
        $this->seedIfEmpty('nec_political_parties', fn() => $this->seedPoliticalParties($now));

        $this->command->info('=== PopulateAllSeeder Complete ===');
        $tables = [
            'nec_voter_accounts', 'nec_voter_transfers', 'nec_categories', 'nec_tags',
            'nec_comments', 'nec_cms_pages', 'nec_menus', 'nec_widgets', 'nec_subscribers',
            'nec_api_keys', 'nec_assets', 'nec_ballots', 'nec_results', 'nec_candidate_results',
            'nec_nominations', 'nec_election_petitions', 'nec_polling_staff', 'nec_complaints',
            'nec_media', 'nec_reports', 'nec_downloads', 'nec_download_stats',
            'nec_events', 'nec_observers', 'nec_security_logs', 'nec_sequences', 'login_logs',
            'nec_political_parties',
        ];
        foreach ($tables as $t) {
            $this->command->info("  $t: " . DB::table($t)->count());
        }
    }

    private function seedIfEmpty(string $table, callable $callback): void
    {
        if (DB::table($table)->count() === 0) {
            $callback();
        }
    }

    private function seedVoterAccounts($now): void
    {
        $voters = DB::table('nec_voters')->where('status', 'active')->limit(100)->get();
        foreach ($voters as $v) {
            $email = $v->email ?: 'voter' . $v->id . '@nec.gov.ss';
            DB::table('nec_voter_accounts')->updateOrInsert(
                ['email' => $email],
                [
                    'voter_id' => $v->id,
                    'email' => $email,
                    'password' => Hash::make('password'),
                    'pin_code' => str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT),
                    'email_verified_at' => rand(0, 1) ? $now->subDays(rand(1, 60)) : null,
                    'last_login' => rand(0, 1) ? $now->subDays(rand(1, 30)) : null,
                    'login_attempts' => 0,
                    'status' => 'active',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
        $this->command->info('Voter accounts: ' . DB::table('nec_voter_accounts')->count());
    }

    private function seedVoterTransfers($now): void
    {
        $voters = DB::table('nec_voters')->where('status', 'active')->limit(15)->get();
        $states = DB::table('nec_states')->pluck('name')->toArray();
        $constituencies = DB::table('nec_constituencies')->pluck('name')->toArray();
        $statuses = ['pending', 'approved', 'rejected', 'cancelled'];

        foreach ($voters as $v) {
            $toState = $states[array_rand($states)];
            $fromState = $states[array_rand($states)];
            $toConst = $constituencies[array_rand($constituencies)];
            $fromConst = $constituencies[array_rand($constituencies)];
            $status = $statuses[array_rand($statuses)];

            DB::table('nec_voter_transfers')->insert([
                'voter_id' => $v->id,
                'voter_identifier' => $v->voter_id,
                'full_name' => $v->full_name,
                'from_state' => $fromState,
                'from_constituency' => $fromConst,
                'to_state' => $toState,
                'to_constituency' => $toConst,
                'reason' => 'Relocated for work/family reasons',
                'status' => $status,
                'reviewed_by' => $status !== 'pending' ? 'admin@nec.gov.ss' : null,
                'reviewed_at' => $status !== 'pending' ? $now->subDays(rand(1, 14)) : null,
                'admin_notes' => $status === 'rejected' ? 'Incomplete documentation provided' : null,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
        $this->command->info('Voter transfers: ' . DB::table('nec_voter_transfers')->count());
    }

    private function seedCategories($now): void
    {
        $cats = [
            ['name' => 'Press Releases', 'slug' => 'press-releases', 'description' => 'Official NEC press releases and statements', 'parent_id' => 0],
            ['name' => 'Election News', 'slug' => 'election-news', 'description' => 'General election-related news and updates', 'parent_id' => 0],
            ['name' => 'Voter Education', 'slug' => 'voter-education', 'description' => 'Educational content for voters', 'parent_id' => 0],
            ['name' => 'Candidate Information', 'slug' => 'candidate-info', 'description' => 'Candidate profiles and nomination information', 'parent_id' => 0],
            ['name' => 'Election Results', 'slug' => 'election-results', 'description' => 'Official election results and statistics', 'parent_id' => 0],
            ['name' => 'Press Statements', 'slug' => 'press-statements', 'description' => 'Short press statements and clarifications', 'parent_id' => 1],
            ['name' => 'Media Advisories', 'slug' => 'media-advisories', 'description' => 'Advisories for media practitioners', 'parent_id' => 1],
            ['name' => 'Registration Updates', 'slug' => 'registration-updates', 'description' => 'Voter registration progress and updates', 'parent_id' => 2],
            ['name' => 'Observer Information', 'slug' => 'observer-info', 'description' => 'Information for election observers', 'parent_id' => 2],
        ];
        foreach ($cats as $c) {
            DB::table('nec_categories')->updateOrInsert(
                ['slug' => $c['slug']],
                array_merge($c, ['status' => 'active', 'created_at' => $now, 'updated_at' => $now])
            );
        }
        $this->command->info('Categories: ' . DB::table('nec_categories')->count());
    }

    private function seedTags($now): void
    {
        $tags = ['2026 Elections', 'Voter Registration', 'Biometric', 'Presidential', 'Parliamentary', 'Observers', 'Women', 'Youth', 'Peace', 'Security', 'Results', 'Nominations', 'Polling Stations', 'NEC Commission', 'Transparency'];
        foreach ($tags as $t) {
            DB::table('nec_tags')->updateOrInsert(
                ['slug' => Str::slug($t)],
                ['name' => $t, 'slug' => Str::slug($t), 'status' => 'active', 'created_at' => $now, 'updated_at' => $now]
            );
        }
        $this->command->info('Tags: ' . DB::table('nec_tags')->count());
    }

    private function seedComments($now): void
    {
        $newsItems = DB::table('nec_news')->where('status', 'published')->get();
        $commenters = [
            ['name' => 'John Kuol', 'email' => 'john.kuol@gmail.com'],
            ['name' => 'Mary Akec', 'email' => 'mary.akec@yahoo.com'],
            ['name' => 'Peter Garang', 'email' => 'peter.garang@hotmail.com'],
            ['name' => 'Sarah Nyakim', 'email' => 'sarah.nyakim@gmail.com'],
            ['name' => 'David Deng', 'email' => 'david.deng@outlook.com'],
            ['name' => 'Grace Lopidia', 'email' => 'grace.lopidia@gmail.com'],
            ['name' => 'James Taban', 'email' => 'james.taban@yahoo.com'],
            ['name' => 'Esther Bol', 'email' => 'esther.bol@gmail.com'],
        ];
        $contents = [
            'This is very informative. Thank you NEC for keeping us updated.',
            'Great progress being made. Looking forward to peaceful elections.',
            'I appreciate the transparency in the electoral process.',
            'When will the full list of polling stations be published?',
            'Important step for democracy in South Sudan.',
            'Hope all citizens will participate in this historic process.',
            'Well done NEC team. Keep up the good work.',
            'More voter education needed in rural areas.',
        ];
        $statuses = ['approved', 'approved', 'approved', 'pending', 'approved', 'spam'];

        foreach ($newsItems as $news) {
            $numComments = rand(1, 3);
            for ($i = 0; $i < $numComments; $i++) {
                $c = $commenters[array_rand($commenters)];
                DB::table('nec_comments')->insert([
                    'post_id' => $news->id,
                    'author_name' => $c['name'],
                    'author_email' => $c['email'],
                    'content' => $contents[array_rand($contents)],
                    'status' => $statuses[array_rand($statuses)],
                    'created_at' => $now->subDays(rand(1, 30)),
                    'updated_at' => $now,
                ]);
            }
        }
        $this->command->info('Comments: ' . DB::table('nec_comments')->count());
    }

    private function seedCmsPages($now): void
    {
        $pages = [
            ['title' => 'About the NEC', 'slug' => 'about', 'content' => '<h2>About the National Electoral Commission</h2><p>The National Electoral Commission (NEC) of South Sudan is the constitutional body responsible for organizing, conducting, and supervising elections in the Republic of South Sudan.</p><p>Established under the Transitional Constitution of the Republic of South Sudan, the NEC operates independently to ensure free, fair, and credible elections.</p>', 'template' => 'default'],
            ['title' => 'Mission and Vision', 'slug' => 'mission-vision', 'content' => '<h2>Our Mission</h2><p>To organize and conduct free, fair, and credible elections that reflect the will of the people of South Sudan.</p><h2>Our Vision</h2><p>A democratic South Sudan where every citizen\'s vote counts and contributes to peaceful governance.</p>', 'template' => 'default'],
            ['title' => 'NEC Commissioners', 'slug' => 'commissioners', 'content' => '<h2>NEC Commissioners</h2><p>The Commission consists of a Chairperson, Deputy Chairperson, and seven Commissioners appointed by the President with the approval of the National Assembly.</p>', 'template' => 'default'],
            ['title' => 'Voter Registration Guide', 'slug' => 'voter-registration-guide', 'content' => '<h2>How to Register to Vote</h2><p>Follow these steps to register as a voter in South Sudan.</p><ol><li>Visit your nearest voter registration center</li><li>Present a valid national ID or passport</li><li>Provide your biometric data (fingerprints and photo)</li><li>Receive your voter registration card</li></ol>', 'template' => 'default'],
            ['title' => 'Election Results Portal', 'slug' => 'results', 'content' => '<h2>Official Election Results</h2><p>This page will display official election results as announced by the National Electoral Commission.</p>', 'template' => 'default'],
            ['title' => 'Contact Us', 'slug' => 'contact', 'content' => '<h2>Contact the NEC</h2><p>Headquarters: Juba, South Sudan<br>Email: info@nec.gov.ss<br>Phone: +211 912 000 001</p>', 'template' => 'default'],
            ['title' => 'Frequently Asked Questions', 'slug' => 'faq', 'content' => '<h2>FAQ</h2><p>Find answers to commonly asked questions about the electoral process in South Sudan.</p>', 'template' => 'default'],
        ];
        foreach ($pages as $p) {
            DB::table('nec_cms_pages')->updateOrInsert(
                ['slug' => $p['slug']],
                array_merge($p, ['status' => 'published', 'created_at' => $now, 'updated_at' => $now])
            );
        }
        $this->command->info('CMS pages: ' . DB::table('nec_cms_pages')->count());
    }

    private function seedMenus($now): void
    {
        $menus = [
            ['name' => 'Main Navigation', 'location' => 'main', 'items' => json_encode([
                ['label' => 'Home', 'url' => '/', 'children' => []],
                ['label' => 'About NEC', 'url' => '/about', 'children' => [
                    ['label' => 'Commissioners', 'url' => '/commissioners'],
                    ['label' => 'Mission & Vision', 'url' => '/mission-vision'],
                ]],
                ['label' => 'Elections', 'url' => '/elections', 'children' => [
                    ['label' => '2026 Schedule', 'url' => '/elections/2026'],
                    ['label' => 'Voter Registration', 'url' => '/voter-registration'],
                    ['label' => 'Results', 'url' => '/results'],
                ]],
                ['label' => 'Media', 'url' => '/media', 'children' => [
                    ['label' => 'News', 'url' => '/news'],
                    ['label' => 'Gallery', 'url' => '/gallery'],
                    ['label' => 'Downloads', 'url' => '/downloads'],
                ]],
                ['label' => 'Contact', 'url' => '/contact'],
            ]), 'status' => 'active'],
            ['name' => 'Footer Quick Links', 'location' => 'footer', 'items' => json_encode([
                ['label' => 'Privacy Policy', 'url' => '/privacy'],
                ['label' => 'Terms of Service', 'url' => '/terms'],
                ['label' => 'FAQ', 'url' => '/faq'],
                ['label' => 'Sitemap', 'url' => '/sitemap'],
            ]), 'status' => 'active'],
            ['name' => 'Voter Services', 'location' => 'sidebar', 'items' => json_encode([
                ['label' => 'Check Registration', 'url' => '/voter-verification'],
                ['label' => 'Transfer Application', 'url' => '/voter-transfer'],
                ['label' => 'Polling Station Locator', 'url' => '/polling-stations'],
                ['label' => 'Submit Complaint', 'url' => '/complaints'],
            ]), 'status' => 'active'],
        ];
        foreach ($menus as $m) {
            DB::table('nec_menus')->updateOrInsert(
                ['location' => $m['location']],
                array_merge($m, ['created_at' => $now, 'updated_at' => $now])
            );
        }
        $this->command->info('Menus: ' . DB::table('nec_menus')->count());
    }

    private function seedWidgets($now): void
    {
        $widgets = [
            ['name' => 'Upcoming Events', 'area' => 'sidebar', 'type' => 'events', 'config' => json_encode(['limit' => 5, 'show_date' => true]), 'order_num' => 1, 'status' => 'active'],
            ['name' => 'Latest News', 'area' => 'sidebar', 'type' => 'news', 'config' => json_encode(['limit' => 5, 'show_excerpt' => true]), 'order_num' => 2, 'status' => 'active'],
            ['name' => 'Quick Stats', 'area' => 'sidebar', 'type' => 'stats', 'config' => json_encode(['show_voters' => true, 'show_stations' => true]), 'order_num' => 3, 'status' => 'active'],
            ['name' => 'Footer About', 'area' => 'footer', 'type' => 'text', 'config' => json_encode(['content' => '<p>National Electoral Commission of South Sudan</p>']), 'order_num' => 1, 'status' => 'active'],
            ['name' => 'Footer Contact', 'area' => 'footer', 'type' => 'contact', 'config' => json_encode(['show_phone' => true, 'show_email' => true]), 'order_num' => 2, 'status' => 'active'],
            ['name' => 'Social Media Links', 'area' => 'footer', 'type' => 'social', 'config' => json_encode(['facebook' => '#', 'twitter' => '#', 'youtube' => '#']), 'order_num' => 3, 'status' => 'active'],
        ];
        foreach ($widgets as $w) {
            DB::table('nec_widgets')->insert(array_merge($w, ['created_at' => $now, 'updated_at' => $now]));
        }
        $this->command->info('Widgets: ' . DB::table('nec_widgets')->count());
    }

    private function seedSubscribers($now): void
    {
        $subs = [
            ['name' => 'John Kuol Deng', 'email' => 'john.kuol@gmail.com', 'source' => 'newsletter'],
            ['name' => 'Mary Akec Lual', 'email' => 'mary.akec@yahoo.com', 'source' => 'newsletter'],
            ['name' => 'Peter Garang Bol', 'email' => 'peter.garang@hotmail.com', 'source' => 'registration'],
            ['name' => 'Sarah Nyakim Machar', 'email' => 'sarah.nyakim@gmail.com', 'source' => 'newsletter'],
            ['name' => 'David Deng Atem', 'email' => 'david.atem@outlook.com', 'source' => 'newsletter'],
            ['name' => 'Grace Lopidia Wani', 'email' => 'grace.lopidia@gmail.com', 'source' => 'newsletter'],
            ['name' => 'James Taban Kuol', 'email' => 'james.taban@yahoo.com', 'source' => 'registration'],
            ['name' => 'Esther Bol Arop', 'email' => 'esther.arop@gmail.com', 'source' => 'newsletter'],
            ['name' => 'Michael Malual Akec', 'email' => 'michael.akec@gmail.com', 'source' => 'newsletter'],
            ['name' => 'Ruth Nundu Lado', 'email' => 'ruth.lado@yahoo.com', 'source' => 'newsletter'],
        ];
        foreach ($subs as $s) {
            DB::table('nec_subscribers')->updateOrInsert(
                ['email' => $s['email']],
                array_merge($s, ['status' => 'active', 'created_at' => $now, 'updated_at' => $now])
            );
        }
        $this->command->info('Subscribers: ' . DB::table('nec_subscribers')->count());
    }

    private function seedApiKeys($now): void
    {
        $keys = [
            ['name' => 'SSRCS Election Data Feed', 'api_key' => Hash::make('api-' . Str::random(32)), 'organization' => 'South Sudan Relief and Crisis Society', 'contact_email' => 'info@ssrcs.org', 'permissions' => 'results,parties', 'rate_limit' => 5000, 'status' => 'active'],
            ['name' => 'UNDP Electoral Support', 'api_key' => Hash::make('api-' . Str::random(32)), 'organization' => 'United Nations Development Programme', 'contact_email' => 'electoral@undp.org', 'permissions' => 'verify,results,candidates', 'rate_limit' => 10000, 'status' => 'active'],
            ['name' => 'AU Election Observation', 'api_key' => Hash::make('api-' . Str::random(32)), 'organization' => 'African Union', 'contact_email' => 'observers@au.int', 'permissions' => 'results', 'rate_limit' => 2000, 'status' => 'active'],
            ['name' => 'SS Broadcasting Corp', 'api_key' => Hash::make('api-' . Str::random(32)), 'organization' => 'South Sudan Broadcasting Corporation', 'contact_email' => 'news@ssbc.org', 'permissions' => 'results,parties', 'rate_limit' => 3000, 'status' => 'active'],
            ['name' => 'Election Data Portal', 'api_key' => Hash::make('api-' . Str::random(32)), 'organization' => 'Open Data South Sudan', 'contact_email' => 'data@opendata.ss', 'permissions' => 'all', 'rate_limit' => 10000, 'status' => 'inactive'],
        ];
        foreach ($keys as $k) {
            DB::table('nec_api_keys')->insert($k);
        }
        $this->command->info('API keys: ' . DB::table('nec_api_keys')->count());
    }

    private function seedAssets($now): void
    {
        $assetTypes = ['ballot_box', 'seal', 'stamp', 'ink', 'form', 'other'];
        $statuses = ['in_stock', 'issued', 'used', 'lost', 'damaged', 'returned'];
        $locations = ['Juba Warehouse', 'Wau Regional Office', 'Malakal Center', 'Bor Compound', 'Rumbek Office', 'Bentiu Depot'];

        for ($i = 1; $i <= 25; $i++) {
            $type = $assetTypes[array_rand($assetTypes)];
            DB::table('nec_assets')->insert([
                'asset_type' => $type,
                'serial_number' => strtoupper(substr($type, 0, 3)) . '-' . str_pad($i, 5, '0', STR_PAD_LEFT),
                'description' => ucfirst(str_replace('_', ' ', $type)) . ' asset item #' . $i,
                'quantity' => rand(10, 500),
                'assigned_to' => rand(0, 1) ? 'staff_' . rand(1, 30) : null,
                'location' => $locations[array_rand($locations)],
                'status' => $statuses[array_rand($statuses)],
                'tracked_by' => 'admin@nec.gov.ss',
                'created_at' => $now->subDays(rand(1, 90)),
                'updated_at' => $now,
            ]);
        }
        $this->command->info('Assets: ' . DB::table('nec_assets')->count());
    }

    private function seedBallots($now): void
    {
        $states = DB::table('nec_states')->pluck('name')->toArray();
        $constituencies = DB::table('nec_constituencies')->pluck('name')->toArray();
        $ballotStatuses = ['designing', 'printing', 'delivered', 'received', 'used', 'audited', 'spoiled'];

        for ($i = 1; $i <= 15; $i++) {
            $constituency = $constituencies[array_rand($constituencies)];
            $state = $states[array_rand($states)];
            $totalPrinted = rand(5000, 50000);
            DB::table('nec_ballots')->insert([
                'election_name' => '2026 General Election',
                'election_type' => $i <= 8 ? 'parliamentary' : 'state_assembly',
                'constituency' => $constituency,
                'state' => $state,
                'ballot_design' => json_encode(['layout' => 'standard', 'columns' => 1, 'font_size' => 12]),
                'candidates' => json_encode(['candidate_1', 'candidate_2', 'candidate_3']),
                'total_printed' => $totalPrinted,
                'serial_start' => 'BAL-' . str_pad($i, 4, '0', STR_PAD_LEFT) . '-001',
                'serial_end' => 'BAL-' . str_pad($i, 4, '0', STR_PAD_LEFT) . '-' . str_pad($totalPrinted, 5, '0', STR_PAD_LEFT),
                'printer' => 'Government Printing Press',
                'delivery_date' => $now->subDays(rand(10, 60))->format('Y-m-d'),
                'received_date' => rand(0, 1) ? $now->subDays(rand(5, 30))->format('Y-m-d') : null,
                'status' => $ballotStatuses[array_rand($ballotStatuses)],
                'notes' => rand(0, 1) ? 'Rush delivery requested' : null,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
        $this->command->info('Ballots: ' . DB::table('nec_ballots')->count());
    }

    private function seedResults($now): void
    {
        $constituencies = DB::table('nec_constituencies')->limit(20)->get();
        $electionTypes = ['parliamentary', 'presidential', 'state_assembly'];

        foreach ($constituencies as $c) {
            $totalVotes = rand(3000, 30000);
            $regVoters = $totalVotes + rand(1000, 10000);
            $turnout = round(($totalVotes / $regVoters) * 100, 2);
            DB::table('nec_results')->insert([
                'election_name' => '2026 General Election',
                'election_type' => $electionTypes[array_rand($electionTypes)],
                'constituency_id' => $c->id,
                'total_votes' => $totalVotes,
                'registered_voters' => $regVoters,
                'turnout' => $turnout,
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
        $this->command->info('Results: ' . DB::table('nec_results')->count());
    }

    private function seedCandidateResults($now): void
    {
        $results = DB::table('nec_results')->get();
        $candidates = DB::table('nec_candidates')->get();
        $parties = DB::table('nec_political_parties')->pluck('name', 'id')->toArray();

        foreach ($results as $r) {
            $numCands = rand(2, 4);
            $assignedCands = $candidates->random(min($numCands, $candidates->count()));
            $totalVotes = 0;
            $rows = [];
            foreach ($assignedCands as $ac) {
                $votes = rand(500, 15000);
                $totalVotes += $votes;
                $rows[] = ['candidate' => $ac, 'votes' => $votes];
            }
            foreach ($rows as $rr) {
                $ac = $rr['candidate'];
                $partyName = isset($parties[$ac->party_id]) ? $parties[$ac->party_id] : null;
                DB::table('nec_candidate_results')->insert([
                    'result_id' => $r->id,
                    'candidate_id' => $ac->id,
                    'candidate_name' => $ac->name,
                    'party_id' => $ac->party_id,
                    'party_name' => $partyName,
                    'votes' => $rr['votes'],
                    'percentage' => $totalVotes > 0 ? round(($rr['votes'] / $totalVotes) * 100, 2) : 0,
                    'status' => 'active',
                ]);
            }
        }
        $this->command->info('Candidate results: ' . DB::table('nec_candidate_results')->count());
    }

    private function seedNominations($now): void
    {
        $candidates = DB::table('nec_candidates')->limit(30)->get();
        $parties = DB::table('nec_political_parties')->pluck('id')->toArray();
        $statuses = ['draft', 'submitted', 'verified', 'approved', 'rejected', 'withdrawn'];

        foreach ($candidates as $c) {
            $status = $statuses[array_rand($statuses)];
            DB::table('nec_nominations')->insert([
                'candidate_name' => $c->name,
                'party_id' => $parties[array_rand($parties)],
                'position' => 'Member of Parliament',
                'constituency' => $c->constituency,
                'state' => $c->state,
                'nominator_name' => 'Registered Voter',
                'nomination_date' => $now->subDays(rand(10, 90))->format('Y-m-d'),
                'status' => $status,
                'reviewed_by' => in_array($status, ['verified', 'approved', 'rejected']) ? 'admin@nec.gov.ss' : null,
                'reviewed_at' => in_array($status, ['verified', 'approved', 'rejected']) ? $now->subDays(rand(1, 10)) : null,
                'admin_notes' => $status === 'rejected' ? 'Incomplete documentation' : null,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
        $this->command->info('Nominations: ' . DB::table('nec_nominations')->count());
    }

    private function seedElectionPetitions($now): void
    {
        $constituencies = DB::table('nec_constituencies')->pluck('name')->toArray();
        $petitioners = [
            ['name' => 'James Kuol Deng', 'respondent' => 'NEC Commission'],
            ['name' => 'Peter Garang Bol', 'respondent' => 'Electoral Officer - Juba'],
            ['name' => 'Sarah Nyakim Machar', 'respondent' => 'NEC - Eastern Equatoria'],
            ['name' => 'David Akec Lual', 'respondent' => 'NEC - Jonglei State'],
            ['name' => 'Michael Malual Atem', 'respondent' => 'Constituency Returning Officer'],
        ];
        $statuses = ['filed', 'pending_hearing', 'in_progress', 'decided', 'closed', 'appealed'];
        $courts = ['Supreme Court of South Sudan', 'High Court - Juba', 'High Court - Wau', 'High Court - Malakal'];

        foreach ($petitioners as $i => $p) {
            $status = $statuses[array_rand($statuses)];
            $petitionNumber = 'PET-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT) . '/' . date('Y');
            DB::table('nec_election_petitions')->updateOrInsert(
                ['petition_number' => $petitionNumber],
                [
                    'petitioner_name' => $p['name'],
                    'respondent_name' => $p['respondent'],
                'election_name' => '2026 General Election',
                'constituency' => $constituencies[array_rand($constituencies)],
                'filing_date' => $now->subDays(rand(5, 60))->format('Y-m-d'),
                'grounds' => 'Alleged irregularities in the electoral process including voter suppression and improper ballot counting.',
                'relief_sought' => 'Requesting a recount of votes and/or annulment of results in the affected constituency.',
                'court_name' => $courts[array_rand($courts)],
                'case_number' => 'CIV/' . rand(100, 999) . '/' . date('Y'),
                'verdict' => $status === 'decided' ? 'Petition dismissed due to lack of sufficient evidence.' : null,
                'verdict_date' => $status === 'decided' ? $now->subDays(rand(1, 5))->format('Y-m-d') : null,
                'status' => $status,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
        $this->command->info('Election petitions: ' . DB::table('nec_election_petitions')->count());
    }

    private function seedPollingStaff($now): void
    {
        $stations = DB::table('nec_polling_stations')->get();
        $roles = ['presiding_officer', 'deputy_presiding', 'poll_clerk', 'security', 'observer', 'trainer'];
        $firstNames = ['James', 'Peter', 'John', 'David', 'Daniel', 'Samuel', 'Joseph', 'Michael', 'Simon', 'Paul', 'Mary', 'Sarah', 'Grace', 'Agnes', 'Ruth', 'Esther', 'Rebecca', 'Hannah', 'Elizabeth', 'Martha'];
        $lastNames = ['Kuir', 'Deng', 'Kuol', 'Bol', 'Atem', 'Garang', 'Kiir', 'Machar', 'Wani', 'Lado', 'Arop', 'Malual', 'Akec', 'Akol', 'Luk', 'Taban', 'Jok', 'Kuany', 'Gatluak', 'Akuek'];

        foreach ($stations as $station) {
            $numStaff = rand(3, 6);
            for ($i = 0; $i < $numStaff; $i++) {
                $fn = $firstNames[array_rand($firstNames)];
                $ln = $lastNames[array_rand($lastNames)];
                $role = $i === 0 ? 'presiding_officer' : $roles[array_rand($roles)];
                DB::table('nec_polling_staff')->insert([
                    'full_name' => $fn . ' ' . $ln,
                    'email' => strtolower($fn . '.' . $ln . rand(1, 99)) . '@nec.gov.ss',
                    'phone' => '+2119' . str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT),
                    'role' => $role,
                    'polling_station_id' => $station->id,
                    'state' => $station->state,
                    'constituency' => $station->constituency,
                    'assignment_date' => $now->subDays(rand(1, 30))->format('Y-m-d'),
                    'trained' => rand(0, 1),
                    'status' => 'active',
                    'notes' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
        $this->command->info('Polling staff: ' . DB::table('nec_polling_staff')->count());
    }

    private function seedComplaints($now): void
    {
        $categories = ['registration', 'voter_card', 'polling_station', 'results', 'observer', 'staff', 'other'];
        $statuses = ['new', 'open', 'in_progress', 'resolved', 'closed', 'escalated'];
        $priorities = ['low', 'medium', 'high', 'urgent'];
        $firstNames = ['James', 'Mary', 'Peter', 'Sarah', 'David', 'Grace', 'John', 'Esther', 'Michael', 'Ruth'];
        $lastNames = ['Kuir', 'Deng', 'Kuol', 'Bol', 'Atem', 'Garang', 'Kiir', 'Machar', 'Wani', 'Lado'];

        $subjects = [
            'Name misspelled on voter card',
            'Did not receive voter card',
            'Polling station too far',
            'Registration center closed',
            'Biometric capture failed',
            'Duplicate registration detected',
            'Observer denied access',
            'Campaigning near polling station',
            'Staff misconduct reported',
            'Results delay at constituency',
        ];

        $descriptions = [
            'My name was incorrectly spelled during voter registration. Please correct it.',
            'I registered over two months ago but have not received my voter registration card.',
            'The assigned polling station is too far from my residence. I request a change.',
            'The registration center was closed when I visited during working hours.',
            'The fingerprint scanner could not read my prints during registration.',
            'I discovered that my relative is registered twice in the system.',
            'An accredited observer was denied access to a polling station.',
            'A candidate is campaigning within 100 meters of a polling station.',
            'A polling station staff member was rude and unprofessional.',
            'Results from my constituency have not been posted yet.',
        ];

        for ($i = 1; $i <= 20; $i++) {
            $fn = $firstNames[array_rand($firstNames)];
            $ln = $lastNames[array_rand($lastNames)];
            $idx = array_rand($subjects);
            $status = $statuses[array_rand($statuses)];
            DB::table('nec_complaints')->insert([
                'voter_identifier' => 'NEC26' . (rand(0, 1) ? 'M' : 'F') . str_pad(rand(1, 200), 6, '0', STR_PAD_LEFT),
                'full_name' => $fn . ' ' . $ln,
                'email' => strtolower($fn . '.' . $ln . '@gmail.com'),
                'phone' => '+2119' . str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT),
                'category' => $categories[array_rand($categories)],
                'subject' => $subjects[$idx],
                'description' => $descriptions[$idx],
                'status' => $status,
                'priority' => $priorities[array_rand($priorities)],
                'assigned_to' => $status !== 'new' ? 'admin@nec.gov.ss' : null,
                'resolution' => in_array($status, ['resolved', 'closed']) ? 'Issue has been addressed. Voter has been contacted.' : null,
                'resolved_at' => in_array($status, ['resolved', 'closed']) ? $now->subDays(rand(1, 10)) : null,
                'resolved_by' => in_array($status, ['resolved', 'closed']) ? 'admin@nec.gov.ss' : null,
                'created_at' => $now->subDays(rand(1, 45)),
                'updated_at' => $now,
            ]);
        }
        $this->command->info('Complaints: ' . DB::table('nec_complaints')->count());
    }

    private function seedMedia($now): void
    {
        $media = [
            ['title' => 'NEC Chairperson Address on Election Preparedness', 'type' => 'video', 'url' => 'https://youtube.com/watch?v=nec-2026-1', 'views' => 15230, 'likes' => 423],
            ['title' => 'How to Register to Vote - Step by Step Guide', 'type' => 'video', 'url' => 'https://youtube.com/watch?v=nec-registration', 'views' => 28450, 'likes' => 891],
            ['title' => 'NEC Stakeholder Engagement Forum Highlights', 'type' => 'video', 'url' => 'https://youtube.com/watch?v=nec-stakeholder', 'views' => 8720, 'likes' => 234],
            ['title' => 'Understanding the Ballot Paper', 'type' => 'video', 'url' => 'https://youtube.com/watch?v=nec-ballot', 'views' => 12100, 'likes' => 345],
            ['title' => 'Voter Registration Progress Report - August 2026', 'type' => 'video', 'url' => 'https://youtube.com/watch?v=nec-progress', 'views' => 6540, 'likes' => 178],
            ['title' => 'NEC Commissioner Interview - Peace and Elections', 'type' => 'video', 'url' => 'https://youtube.com/watch?v=nec-interview', 'views' => 4310, 'likes' => 112],
            ['title' => 'Official NEC Logo', 'type' => 'image', 'url' => '/images/media/nec-logo.png', 'views' => 0, 'likes' => 0],
            ['title' => '2026 Election Timeline Infographic', 'type' => 'image', 'url' => '/images/media/timeline-2026.png', 'views' => 0, 'likes' => 0],
            ['title' => 'NEC Organizational Structure', 'type' => 'document', 'url' => '/downloads/org-chart.pdf', 'views' => 0, 'likes' => 0],
            ['title' => 'Election Day Procedures Manual', 'type' => 'document', 'url' => '/downloads/election-day-manual.pdf', 'views' => 0, 'likes' => 0],
        ];
        foreach ($media as $m) {
            DB::table('nec_media')->insert(array_merge($m, [
                'description' => substr($m['title'], 0, 50),
                'thumbnail' => '/images/media/thumbs/' . Str::slug($m['title']) . '.jpg',
                'status' => 'published',
                'created_at' => $now->subDays(rand(1, 60)),
                'updated_at' => $now,
            ]));
        }
        $this->command->info('Media: ' . DB::table('nec_media')->count());
    }

    private function seedReports($now): void
    {
        $reports = [
            ['title' => 'Monthly Voter Registration Report - March 2026', 'category' => 'registration', 'report_date' => '2026-03-31'],
            ['title' => 'Monthly Voter Registration Report - April 2026', 'category' => 'registration', 'report_date' => '2026-04-30'],
            ['title' => 'Monthly Voter Registration Report - May 2026', 'category' => 'registration', 'report_date' => '2026-05-31'],
            ['title' => 'Quarterly Election Preparedness Report - Q1 2026', 'category' => 'preparedness', 'report_date' => '2026-03-31'],
            ['title' => 'Quarterly Election Preparedness Report - Q2 2026', 'category' => 'preparedness', 'report_date' => '2026-06-30'],
            ['title' => 'Budget Utilization Report - First Half 2026', 'category' => 'finance', 'report_date' => '2026-06-30'],
            ['title' => 'Observer Accreditation Status Report', 'category' => 'observers', 'report_date' => '2026-07-15'],
            ['title' => 'Complaint Resolution Statistics - Q2 2026', 'category' => 'complaints', 'report_date' => '2026-06-30'],
            ['title' => 'Biometric Equipment Deployment Status', 'category' => 'logistics', 'report_date' => '2026-07-01'],
            ['title' => 'Security Assessment Report - Polling Stations', 'category' => 'security', 'report_date' => '2026-07-20'],
        ];
        foreach ($reports as $r) {
            DB::table('nec_reports')->insert(array_merge($r, [
                'description' => $r['title'] . ' - detailed analysis and statistics.',
                'file_path' => '/files/reports/' . Str::slug($r['title']) . '.pdf',
                'status' => 'published',
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }
        $this->command->info('Reports: ' . DB::table('nec_reports')->count());
    }

    private function seedDownloads($now): void
    {
        $downloads = [
            ['title' => 'Voter Registration Form', 'category' => 'forms', 'file_path' => '/downloads/voter-registration-form.pdf', 'file_size' => '2.4 MB', 'file_type' => 'PDF', 'downloads_count' => 1250],
            ['title' => 'Candidate Nomination Form', 'category' => 'forms', 'file_path' => '/downloads/nomination-form.pdf', 'file_size' => '1.8 MB', 'file_type' => 'PDF', 'downloads_count' => 340],
            ['title' => 'Voter Transfer Application Form', 'category' => 'forms', 'file_path' => '/downloads/transfer-form.pdf', 'file_size' => '1.2 MB', 'file_type' => 'PDF', 'downloads_count' => 560],
            ['title' => 'Election Observer Accreditation Form', 'category' => 'forms', 'file_path' => '/downloads/observer-form.pdf', 'file_size' => '3.1 MB', 'file_type' => 'PDF', 'downloads_count' => 180],
            ['title' => '2026 Election Timeline', 'category' => 'publications', 'file_path' => '/downloads/election-timeline-2026.pdf', 'file_size' => '0.8 MB', 'file_type' => 'PDF', 'downloads_count' => 2780],
            ['title' => 'Voter Education Brochure - English', 'category' => 'publications', 'file_path' => '/downloads/voter-education-en.pdf', 'file_size' => '5.2 MB', 'file_type' => 'PDF', 'downloads_count' => 3420],
            ['title' => 'Voter Education Brochure - Arabic', 'category' => 'publications', 'file_path' => '/downloads/voter-education-ar.pdf', 'file_size' => '5.2 MB', 'file_type' => 'PDF', 'downloads_count' => 890],
            ['title' => 'Electoral Code of Conduct', 'category' => 'legal', 'file_path' => '/downloads/code-of-conduct.pdf', 'file_size' => '1.5 MB', 'file_type' => 'PDF', 'downloads_count' => 670],
            ['title' => 'Political Parties Registration Guidelines', 'category' => 'legal', 'file_path' => '/downloads/party-guidelines.pdf', 'file_size' => '2.0 MB', 'file_type' => 'PDF', 'downloads_count' => 230],
            ['title' => 'NEC Annual Report 2025', 'category' => 'reports', 'file_path' => '/downloads/annual-report-2025.pdf', 'file_size' => '8.4 MB', 'file_type' => 'PDF', 'downloads_count' => 420],
        ];
        foreach ($downloads as $d) {
            DB::table('nec_downloads')->insert(array_merge($d, [
                'description' => $d['title'],
                'status' => 'published',
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }
        $this->command->info('Downloads: ' . DB::table('nec_downloads')->count());
    }

    private function seedDownloadStats($now): void
    {
        $stats = [
            ['slug' => 'voter-registration-form', 'label' => 'Voter Registration Form', 'url' => '/downloads/voter-registration-form.pdf', 'count' => 1250],
            ['slug' => 'election-timeline', 'label' => '2026 Election Timeline', 'url' => '/downloads/election-timeline-2026.pdf', 'count' => 2780],
            ['slug' => 'voter-education-en', 'label' => 'Voter Education Brochure - English', 'url' => '/downloads/voter-education-en.pdf', 'count' => 3420],
            ['slug' => 'code-of-conduct', 'label' => 'Electoral Code of Conduct', 'url' => '/downloads/code-of-conduct.pdf', 'count' => 670],
            ['slug' => 'annual-report-2025', 'label' => 'NEC Annual Report 2025', 'url' => '/downloads/annual-report-2025.pdf', 'count' => 420],
        ];
        foreach ($stats as $s) {
            DB::table('nec_download_stats')->updateOrInsert(
                ['slug' => $s['slug']],
                array_merge($s, ['created_at' => $now, 'updated_at' => $now])
            );
        }
        $this->command->info('Download stats: ' . DB::table('nec_download_stats')->count());
    }

    private function seedObservers($now): void
    {
        $approved = DB::table('nec_observer_applications')->where('status', 'approved')->get();
        foreach ($approved as $a) {
            DB::table('nec_observers')->updateOrInsert(
                ['email' => $a->email],
                [
                    'email' => $a->email,
                    'password' => Hash::make('password'),
                    'last_name' => $a->last_name,
                    'other_names' => ($a->first_name . ' ' . ($a->other_names ?? '')) ?: $a->first_name,
                    'title' => $a->title,
                    'gender' => $a->gender,
                    'national_id' => $a->national_id,
                    'phone' => $a->phone,
                    'residential_address' => $a->residential_address,
                    'observer_type' => $a->observer_type === 'international' ? 'individual' : 'individual',
                    'category' => $a->observer_type === 'international' ? 'international' : 'domestic',
                    'nationality' => $a->nationality ?: 'South Sudanese',
                    'organisation_name' => $a->organization_name,
                    'status' => 'accredited',
                    'verified_at' => $now->subDays(rand(1, 30)),
                    'accredited_at' => $now->subDays(rand(1, 15)),
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
        $this->command->info('Observers: ' . DB::table('nec_observers')->count());
    }

    private function seedSecurityLogs($now): void
    {
        $events = ['login_success', 'login_failed', 'logout', 'password_change', 'api_access', 'unauthorized_attempt', 'account_locked', 'permission_change'];
        $users = DB::table('nec_users')->pluck('email')->toArray();

        for ($i = 1; $i <= 50; $i++) {
            $event = $events[array_rand($events)];
            $userEmail = $users[array_rand($users)];
            DB::table('nec_security_logs')->insert([
                'event_type' => $event,
                'details' => ucfirst(str_replace('_', ' ', $event)) . ' for user ' . $userEmail,
                'request_data' => json_encode(['ip' => '192.168.' . rand(1, 255) . '.' . rand(1, 255), 'user_agent' => 'Mozilla/5.0']),
                'ip_address' => '192.168.' . rand(1, 255) . '.' . rand(1, 255),
                'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36',
                'request_uri' => '/' . ['admin', 'api', 'login', 'dashboard'][array_rand(['admin', 'api', 'login', 'dashboard'])],
                'user_email' => $event === 'login_failed' ? 'unknown@attempt.com' : $userEmail,
                'created_at' => $now->subMinutes(rand(1, 43200)),
            ]);
        }
        $this->command->info('Security logs: ' . DB::table('nec_security_logs')->count());
    }

    private function seedSequences($now): void
    {
        $seqs = [
            ['seq_name' => 'voter_id', 'seq_value' => 200],
            ['seq_name' => 'voter_transfer', 'seq_value' => 15],
            ['seq_name' => 'complaint', 'seq_value' => 20],
            ['seq_name' => 'petition', 'seq_value' => 5],
            ['seq_name' => 'ballot', 'seq_value' => 15],
        ];
        foreach ($seqs as $s) {
            DB::table('nec_sequences')->updateOrInsert(
                ['seq_name' => $s['seq_name']],
                ['seq_value' => $s['seq_value']]
            );
        }
        $this->command->info('Sequences: ' . DB::table('nec_sequences')->count());
    }

    private function seedEvents($now): void
    {
        $events = [
            ['title' => 'Voter Registration Opening Ceremony', 'slug' => 'voter-registration-opening', 'description' => 'Official opening of voter registration centers nationwide for the 2026 general elections.', 'location' => 'NEC Headquarters, Juba', 'start_date' => '2026-01-15 08:00:00', 'end_date' => '2026-01-15 17:00:00', 'organizer' => 'NEC Commission', 'event_type' => 'registration', 'status' => 'published', 'views' => 1240],
            ['title' => 'Candidate Nomination Period Opens', 'slug' => 'candidate-nomination-opens', 'description' => 'Opening of the candidate nomination period for presidential, parliamentary, and state assembly elections.', 'location' => 'NEC Headquarters, Juba', 'start_date' => '2026-03-01 08:00:00', 'end_date' => '2026-03-30 17:00:00', 'organizer' => 'NEC Elections Department', 'event_type' => 'nomination', 'status' => 'published', 'views' => 890],
            ['title' => 'Presidential Debate - Juba', 'slug' => 'presidential-debate-juba', 'description' => 'First round of presidential debates hosted at the Parliament Building in Juba.', 'location' => 'Parliament Building, Juba', 'start_date' => '2026-09-15 14:00:00', 'end_date' => '2026-09-15 18:00:00', 'organizer' => 'NEC Media', 'event_type' => 'public', 'status' => 'published', 'views' => 3200],
            ['title' => 'Election Day - 2026 General Elections', 'slug' => 'election-day-2026', 'description' => 'Voting day for the 2026 General Elections. Polling stations open from 7:00 AM to 5:00 PM.', 'location' => 'Nationwide', 'start_date' => '2026-12-15 07:00:00', 'end_date' => '2026-12-15 17:00:00', 'organizer' => 'NEC Commission', 'event_type' => 'election', 'status' => 'published', 'views' => 5600],
            ['title' => 'Results Announcement Press Conference', 'slug' => 'results-announcement', 'description' => 'Official announcement of preliminary election results by the NEC Chairperson.', 'location' => 'NEC Headquarters, Juba', 'start_date' => '2026-12-20 10:00:00', 'end_date' => '2026-12-20 12:00:00', 'organizer' => 'NEC Commission', 'event_type' => 'public', 'status' => 'published', 'views' => 4100],
            ['title' => 'Electoral Stakeholders Forum', 'slug' => 'stakeholders-forum-2026', 'description' => 'Annual forum for electoral stakeholders including political parties, civil society, and international partners.', 'location' => 'Freedom Hall, Juba', 'start_date' => '2026-02-20 09:00:00', 'end_date' => '2026-02-21 17:00:00', 'organizer' => 'NEC Commission', 'event_type' => 'public', 'status' => 'published', 'views' => 670],
            ['title' => 'International Observers Briefing', 'slug' => 'observers-briefing-2026', 'description' => 'Briefing session for accredited international election observers.', 'location' => 'Radisson Blu Hotel, Juba', 'start_date' => '2026-12-10 09:00:00', 'end_date' => '2026-12-10 16:00:00', 'organizer' => 'NEC External Relations', 'event_type' => 'public', 'status' => 'published', 'views' => 430],
            ['title' => 'National Voter Education Week', 'slug' => 'voter-education-week', 'description' => 'Nationwide voter education campaign featuring radio programs, community meetings, and school visits.', 'location' => 'All States', 'start_date' => '2026-08-01 08:00:00', 'end_date' => '2026-08-07 17:00:00', 'organizer' => 'NEC Voter Education Unit', 'event_type' => 'education', 'status' => 'published', 'views' => 2150],
        ];
        foreach ($events as $e) {
            DB::table('nec_events')->updateOrInsert(
                ['slug' => $e['slug']],
                array_merge($e, ['created_at' => $now, 'updated_at' => $now])
            );
        }
        $this->command->info('Events: ' . DB::table('nec_events')->count());
    }

    private function seedLoginLogs($now): void
    {
        $users = DB::table('nec_users')->select('email', 'name', 'role')->get();
        foreach ($users as $u) {
            DB::table('login_logs')->insert([
                'identifier' => $u->email,
                'name' => $u->name,
                'ip_address' => '192.168.' . rand(1, 255) . '.' . rand(1, 255),
                'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36',
                'location' => 'Juba, South Sudan',
                'success' => rand(0, 1),
                'role' => $u->role,
                'logged_at' => $now->subHours(rand(1, 720)),
                'created_at' => $now->subHours(rand(1, 720)),
            ]);
        }
        $this->command->info('Login logs: ' . DB::table('login_logs')->count());
    }

    private function seedPoliticalParties($now): void
    {
        $parties = [
            ['name' => 'African National Congress', 'acronym' => 'ANC', 'leader' => 'Gen. (Rtd) George Kongor Arop', 'color' => '#FFFFFF', 'status' => 'active'],
            ['name' => "Sudan People's Liberation Movement", 'acronym' => 'SPLM', 'leader' => 'Gen. Salva Kiir Mayardit', 'color' => '#ED1B24', 'status' => 'active'],
            ['name' => 'United Sudan African Party', 'acronym' => 'USAP', 'leader' => 'Hon. Joseph Malek Arop', 'color' => '#FFFFFF', 'status' => 'active'],
            ['name' => 'United Democratic Salvation Front - Mainstream', 'acronym' => 'UDSF-M', 'leader' => 'Hon. Francis Ben Ataba', 'color' => '#008000', 'status' => 'active'],
            ['name' => 'National Liberation Party', 'acronym' => 'NLP', 'leader' => 'Hon. Nkurumah Anai', 'color' => '#0000FF', 'status' => 'active'],
            ['name' => 'National Congress Party', 'acronym' => 'NCP', 'leader' => 'Hon. Agnes Poni Lukudu', 'color' => '#90EE90', 'status' => 'active'],
            ['name' => 'Democratic Change Party', 'acronym' => 'DC', 'leader' => 'Hon. Onyoti Adigo Nykuac', 'color' => '#0000FF', 'status' => 'active'],
            ['name' => 'South Sudan Democratic Forum', 'acronym' => 'SSDF', 'leader' => 'Hon. Dr. Martin Elia Lomuro', 'color' => '#FFA500', 'status' => 'active'],
            ['name' => 'United South Sudan Party', 'acronym' => 'USSP', 'leader' => 'Hon. Paulino Lukudu Obede', 'color' => '#000080', 'status' => 'active'],
            ['name' => 'National United Democratic Front', 'acronym' => 'NUDF', 'leader' => 'Hon. Kornelio Kon Ngu', 'color' => '#FFFFFF', 'status' => 'active'],
            ['name' => 'South Sudan Democratic Alliance', 'acronym' => 'SSDA', 'leader' => 'Hon. Pasqulina Phillip Waden', 'color' => '#87CEEB', 'status' => 'active'],
            ['name' => 'Sudan African National Union - National', 'acronym' => 'SANU-N', 'leader' => 'Hon. Theresa Ciricio Iro', 'color' => '#0000FF', 'status' => 'active'],
            ['name' => 'United Democratic Salvation Front', 'acronym' => 'UDSF', 'leader' => 'Hon. Rev. Emmanuel Sokiri', 'color' => '#008000', 'status' => 'active'],
            ['name' => 'National Democratic Party', 'acronym' => 'NDP', 'leader' => 'Hon. James Aniceto', 'color' => '#004225', 'status' => 'active'],
            ['name' => 'United Democratic Party', 'acronym' => 'UDP', 'leader' => 'Hon. Tong Lual Ayat', 'color' => '#FFA500', 'status' => 'active'],
            ['name' => 'Federal Democratic Party', 'acronym' => 'FDP', 'leader' => 'Hon. Galdong Nganyek Bhok', 'color' => '#008000', 'status' => 'active'],
            ['name' => 'United Democratic Front', 'acronym' => 'UDF', 'leader' => 'Hon. Bona Deng', 'color' => '#FFFFFF', 'status' => 'active'],
            ['name' => 'Communist Party of South Sudan', 'acronym' => 'CPSS', 'leader' => 'Hon. Joseph Wol Modesto', 'color' => '#000000', 'status' => 'active'],
            ['name' => 'Democratic Unionist Party', 'acronym' => 'DUP', 'leader' => 'Hon. Albino John Lako', 'color' => '#008000', 'status' => 'active'],
            ['name' => 'South Sudan African National Union', 'acronym' => 'SSANU', 'leader' => 'Hon. Philip Palet', 'color' => '#008000', 'status' => 'active'],
            ['name' => 'Generation Party', 'acronym' => 'GP', 'leader' => 'Hon. Looth Mah Tang', 'color' => '#008000', 'status' => 'active'],
            ['name' => 'South Sudan National Party', 'acronym' => 'SSNP', 'leader' => 'Hon. Juma Said W', 'color' => '#FFFFFF', 'status' => 'active'],
            ['name' => 'National Democratic Front', 'acronym' => 'NDF', 'leader' => 'Hon. Stephen Goro', 'color' => '#008000', 'status' => 'active'],
            ['name' => 'Republican Party of South Sudan', 'acronym' => 'RPSS', 'leader' => 'Hon. Anthony Agiem', 'color' => '#FFFF00', 'status' => 'active'],
            ['name' => 'Akut Bam Party', 'acronym' => 'ABP', 'leader' => 'Hon. Makuac Akol', 'color' => '#008000', 'status' => 'active'],
            ['name' => 'Popular Congress Party', 'acronym' => 'PCP', 'leader' => 'Hon. Abdalla Deng Nhial', 'color' => '#87CEEB', 'status' => 'active'],
            ['name' => 'South Sudan Generation Party', 'acronym' => 'SSGP', 'leader' => 'Adv. Mayen Jeramiah Turc', 'color' => '#FFFF00', 'status' => 'active'],
            ['name' => 'National Justice Movement Party', 'acronym' => 'NJMP', 'leader' => 'Hon. Mater Mayind', 'color' => '#FFFFFF', 'status' => 'active'],
            ['name' => 'South Sudan National Labor Party', 'acronym' => 'SSNLP', 'leader' => 'Hon. James Andrea Anyak', 'color' => '#008000', 'status' => 'active'],
            ['name' => 'Social Democratic Party', 'acronym' => 'SDP', 'leader' => 'Mrs. Rain Ayen Deng', 'color' => '#008000', 'status' => 'active'],
            ['name' => 'National Patriotic Movement', 'acronym' => 'NPM', 'leader' => 'Hon. Dr. Isaa Muzamil', 'color' => '#FFFF00', 'status' => 'active'],
            ['name' => 'South Sudan Democratic Front', 'acronym' => 'SSDF', 'leader' => 'Hon. Prof. David De Chan', 'color' => '#0000FF', 'status' => 'active'],
            ['name' => "Peoples' United Forum", 'acronym' => 'PUF', 'leader' => 'Dr. Gai Chol Paul', 'color' => '#008000', 'status' => 'active'],
            ['name' => "People's Democratic Movement", 'acronym' => 'PDM', 'leader' => 'H.E. Josephine Lagu', 'color' => '#FFFFFF', 'status' => 'active'],
            ['name' => 'IO Party', 'acronym' => 'IOP', 'leader' => 'Hon. Amb. Stephen Par Koul', 'color' => '#ADD8E6', 'status' => 'active'],
            ['name' => 'National Democratic Movement', 'acronym' => 'NDM', 'leader' => 'Hon. Dr. Lam Akol Ajawin', 'color' => '#0000FF', 'status' => 'active'],
            ['name' => 'South Sudan National Movement for Change', 'acronym' => 'SSNMC', 'leader' => 'Hon. Moro Isaac Jenesio', 'color' => '#ADD8E6', 'status' => 'active'],
            ['name' => 'People Liberal Party', 'acronym' => 'PLP', 'leader' => 'Hon. Peter Mayen Majongdit', 'color' => '#ED1B24', 'status' => 'active'],
            ['name' => 'Revive South Sudan Party', 'acronym' => 'RSSP', 'leader' => 'Hon. Mawien Dot Pheot', 'color' => '#90EE90', 'status' => 'active'],
        ];

        foreach ($parties as $p) {
            DB::table('nec_political_parties')->updateOrInsert(
                ['name' => $p['name']],
                array_merge($p, [
                    'founded' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ])
            );
        }
        $this->command->info('Political parties: ' . DB::table('nec_political_parties')->count());
    }
}
