<?php

namespace Tests\Feature;

use App\Models\Voter;
use App\Helpers\NecHelper;
use Tests\TestCase;

class VoterAgeEligibilityTest extends TestCase
{
    private function payload(array $overrides = []): array
    {
        return array_merge([
            'full_name' => 'Eligibility Test Voter',
            'gender' => 'M',
            'dob' => '2005-06-15',
            'phone' => '091' . str_pad((string) random_int(0, 99999999), 8, '0', STR_PAD_LEFT),
            'email' => 'elig' . uniqid() . '@example.com',
            'location_type' => 'ss',
            'nationality' => 'South Sudanese',
            'preferred_language' => 'English',
            'registration_type' => 'self',
            'national_id' => 'NID-ELIG-' . uniqid(),
            'state' => 'Central Equatoria',
            'county' => 'Juba County',
            'constituency' => 'Juba',
            'payam' => 'Juba Town',
            'boma' => 'Kator',
            'polling_station' => 'Juba Primary School',
        ], $overrides);
    }

    public function test_server_rejects_under_minimum_registration_age()
    {
        $born = NecHelper::minimum_registration_age() === 16 ? '2011-01-01' : '1999-06-15';
        $response = $this->post(route('voter.register.submit'), $this->payload(['dob' => $born]));

        $response->assertSessionHasErrors('dob');
        $this->assertNull(session('voter_pending'));
    }

    public function test_server_accepts_minimum_age_boundary()
    {
        $year = (int) config('nec.election_year') - NecHelper::minimum_registration_age();
        $response = $this->post(route('voter.register.submit'), $this->payload(['dob' => "{$year}-12-31"]));

        $response->assertOk();
        $this->assertNotNull(session('voter_pending'));
    }

    public function test_pre_registrant_is_stored_with_eligibility_fields()
    {
        $id = uniqid();
        $this->post(route('voter.register.submit'), $this->payload([
            'dob' => '2010-01-01',
            'national_id' => 'NID-PRE-' . $id,
        ]));

        $this->post(route('voter.register.verify-otp'), ['otp' => '000000']);

        $voter = Voter::where('national_id', 'NID-PRE-' . $id)->first();
        $this->assertNotNull($voter);
        $this->assertTrue((bool) $voter->pre_registered);
        $this->assertFalse((bool) $voter->eligible_to_vote);
        $this->assertEquals('2028-01-01', $voter->eligibility_date->format('Y-m-d'));
        $this->assertFalse($voter->isEligibleToVote());
    }

    public function test_adult_registrant_is_stored_as_eligible()
    {
        $id = uniqid();
        $this->post(route('voter.register.submit'), $this->payload([
            'dob' => '2005-06-15',
            'national_id' => 'NID-ADU-' . $id,
        ]));

        $this->post(route('voter.register.verify-otp'), ['otp' => '000000']);

        $voter = Voter::where('national_id', 'NID-ADU-' . $id)->first();
        $this->assertNotNull($voter);
        $this->assertFalse((bool) $voter->pre_registered);
        $this->assertTrue((bool) $voter->isEligibleToVote());
    }

    public function test_model_eligible_boundary()
    {
        $year = (int) config('nec.election_year') - 18;
        $eligible = Voter::create([
            'voter_id' => 'ID-' . uniqid(),
            'full_name' => 'Boundary Eligible',
            'dob' => "{$year}-12-31",
        ]);
        $this->assertTrue($eligible->isEligibleToVote());

        $notYet = Voter::create([
            'voter_id' => 'ID-' . uniqid(),
            'full_name' => 'Boundary Not Yet',
            'dob' => ($year + 1) . '-01-01',
        ]);
        $this->assertFalse($notYet->isEligibleToVote());
        $this->assertTrue($notYet->isPreRegistered());
    }

    public function test_helper_eligibility_date()
    {
        $dob = \Carbon\Carbon::parse('2008-02-29');
        $this->assertEquals('2026-03-01', NecHelper::eligibility_date($dob)->format('Y-m-d'));

        $dob = \Carbon\Carbon::parse('2008-07-01');
        $this->assertEquals('2026-07-01', NecHelper::eligibility_date($dob)->format('Y-m-d'));
    }
}