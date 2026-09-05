<?php

namespace Tests\Feature;

use App\Models\Voter;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LocationStatisticsTest extends TestCase
{
    use DatabaseTransactions;
    use WithFaker;

    private function makeVoter(array $overrides = []): Voter
    {
        return Voter::create(array_merge([
            'voter_id' => 'VOT-' . strtoupper(uniqid()),
            'full_name' => $this->faker()->name,
            'dob' => '1990-01-01',
            'gender' => 'M',
            'national_id' => (string) random_int(1000000000, 9999999999),
            'phone' => '+211' . random_int(900000000, 999999999),
            'email' => $this->faker()->safeEmail,
            'state' => 'Test State Alpha',
            'county' => 'Test County One',
            'constituency' => 'Test Constituency North',
            'polling_station' => 'Primary School A',
            'status' => 'active',
            'registered_at' => '2026-01-01 10:00:00',
            'eligible_to_vote' => true,
            'pre_registered' => false,
        ], $overrides));
    }

    public function test_public_stats_page_renders()
    {
        $this->makeVoter();

        $this->get(route('reports.voter-stats'))
            ->assertOk()
            ->assertSee('Voter Statistics by Location')
            ->assertSee('Registered Voters')
            ->assertSee('Eligible to Vote');
    }

    public function test_default_view_groups_by_state()
    {
        $this->makeVoter(['state' => 'Test State Alpha']);
        $this->makeVoter(['state' => 'Test State Beta']);

        $this->get(route('reports.voter-stats'))
            ->assertOk()
            ->assertSee('Test State Alpha')
            ->assertSee('Test State Beta');
    }

    public function test_state_filter_drills_down_to_county()
    {
        $this->makeVoter(['state' => 'Test State Alpha', 'county' => 'Test County One']);
        $this->makeVoter(['state' => 'Test State Alpha', 'county' => 'Test County Two']);
        $this->makeVoter(['state' => 'Test State Beta', 'county' => 'Test County Three']);

        $this->get(route('reports.voter-stats', ['state' => 'Test State Alpha']))
            ->assertOk()
            ->assertSee('Test County One')
            ->assertSee('Test County Two')
            ->assertDontSee('Test County Three');
    }

    public function test_county_filter_drills_down_to_constituency()
    {
        $this->makeVoter(['state' => 'Test State Alpha', 'county' => 'Test County One', 'constituency' => 'Test Constituency North']);
        $this->makeVoter(['state' => 'Test State Alpha', 'county' => 'Test County One', 'constituency' => 'Test Constituency South']);

        $response = $this->get(route('reports.voter-stats', [
            'state' => 'Test State Alpha',
            'county' => 'Test County One',
        ]));

        $response->assertOk()
            ->assertSee('Test Constituency North')
            ->assertSee('Test Constituency South');
    }

    public function test_eligible_pre_registered_and_deceased_are_aggregated_correctly()
    {
        $this->makeVoter(['state' => 'Test State Alpha', 'eligible_to_vote' => true, 'pre_registered' => false, 'gender' => 'M']);
        $this->makeVoter(['state' => 'Test State Alpha', 'eligible_to_vote' => false, 'pre_registered' => true, 'dob' => '2010-01-01', 'gender' => 'F']);
        $deceased = $this->makeVoter(['state' => 'Test State Alpha', 'eligible_to_vote' => true, 'gender' => 'F']);
        $deceased->markAsDeceased(['deceased_date' => '2026-08-01', 'deceased_by' => 'Test Admin']);

        $response = $this->get(route('reports.voter-stats.export', ['state' => 'Test State Alpha']));

        $response->assertOk();
        $csv = $response->streamedContent();

        // County row: Registered=3, Eligible=1 (deceased excluded), Pre-Reg=1, Pending=0, Deceased=1, Male=1, Female=1, Agent=0
        $this->assertStringContainsString('"Test County One",3,1,1,0,1,1,1,0', $csv);
    }

    public function test_csv_export_contains_aggregates_without_payload()
    {
        $this->makeVoter(['state' => 'Test State Alpha', 'county' => 'Test County One', 'eligible_to_vote' => true, 'gender' => 'M']);
        $this->makeVoter(['state' => 'Test State Alpha', 'county' => 'Test County One', 'eligible_to_vote' => false, 'pre_registered' => true, 'dob' => '2010-01-01', 'gender' => 'F']);

        $response = $this->get(route('reports.voter-stats.export', ['state' => 'Test State Alpha', 'county' => 'Test County One']));

        $response->assertOk();
        $response->assertDownload();

        $csv = $response->streamedContent();
        $this->assertStringContainsString('Constituency,Registered,Eligible', $csv);
        // Constituency row: Registered=2, Eligible=1, Pre-Reg=1, Pending=0, Deceased=0, Male=1, Female=1, Agent=0
        $this->assertStringContainsString('"Test Constituency North",2,1,1,0,0,1,1,0', $csv);
        $this->assertStringNotContainsString('national_id', strtolower($csv));
    }

    public function test_admin_export_honours_location_filters()
    {
        session([
            'admin_logged_in' => true,
            'admin_user_id' => 1,
            'admin_role' => 'super_admin',
            'admin_user_name' => 'Test Admin',
        ]);

        $this->makeVoter(['state' => 'Test State Alpha', 'full_name' => 'ALPHA RESIDENT ONE']);
        $this->makeVoter(['state' => 'Test State Beta', 'full_name' => 'BETA RESIDENT TWO']);

        $response = $this->get(route('admin.voters.export', ['state' => 'Test State Alpha']));

        $response->assertOk();
        $csv = $response->streamedContent();
        $this->assertStringContainsString('ALPHA RESIDENT ONE', $csv);
        $this->assertStringNotContainsString('BETA RESIDENT TWO', $csv);
    }

    public function test_invalid_group_specifier_falls_back_safely()
    {
        $this->makeVoter(['state' => 'Test State Alpha']);

        $this->get(route('reports.voter-stats', ['state' => 'Test State Alpha', 'county' => '', 'constituency' => '']))
            ->assertOk()
            ->assertSee('Test County One');
    }
}