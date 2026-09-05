<?php

namespace Tests\Feature;

use App\Models\SecurityLog;
use App\Models\Voter;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class VoterDeceasedTest extends TestCase
{
    use DatabaseTransactions;
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        session([
            'admin_logged_in' => true,
            'admin_user_id' => 1,
            'admin_role' => 'super_admin',
            'admin_user_name' => 'Test Admin',
        ]);
    }

    private function makeVoter(): Voter
    {
        return Voter::create([
            'voter_id' => 'VOT-' . strtoupper(uniqid()),
            'full_name' => $this->faker()->name,
            'dob' => '1990-01-01',
            'gender' => 'M',
            'national_id' => (string) random_int(1000000000, 9999999999),
            'phone' => '+211' . random_int(900000000, 999999999),
            'email' => $this->faker()->safeEmail,
            'state' => 'Central Equatoria',
            'county' => 'Juba',
            'constituency' => 'Juba County',
            'status' => 'active',
            'registered_at' => '2026-01-01 10:00:00',
            'eligible_to_vote' => true,
        ]);
    }

    public function test_eligible_voter_becomes_ineligible_when_deceased()
    {
        $voter = $this->makeVoter();
        $this->assertTrue($voter->isEligibleToVote());

        $voter->markAsDeceased(['deceased_date' => '2026-08-01']);
        $voter->refresh();

        $this->assertTrue($voter->isDeceased());
        $this->assertFalse($voter->isEligibleToVote());
    }

    public function test_is_deceased_returns_true_by_status_or_date()
    {
        $voter = $this->makeVoter();
        $voter->update(['status' => 'deceased']);
        $this->assertTrue($voter->isDeceased());

        $voter2 = $this->makeVoter();
        $voter2->forceFill(['deceased_date' => '2026-07-15'])->save();
        $this->assertTrue($voter2->isDeceased());
    }

    public function test_admin_records_death_and_logs_activity()
    {
        $voter = $this->makeVoter();

        $response = $this->post(route('admin.voters.deceased', $voter->id), [
            'deceased_date' => '2026-08-20',
            'death_certificate_ref' => 'DC-2026-0001',
        ]);

        $response->assertSessionHas('success');
        $response->assertRedirect();

        $voter->refresh();
        $this->assertEquals('deceased', $voter->status);
        $this->assertEquals('2026-08-20', $voter->deceased_date->format('Y-m-d'));
        $this->assertEquals('DC-2026-0001', $voter->death_certificate_ref);
        $this->assertNotNull($voter->deceased_at);
        $this->assertEquals('Test Admin', $voter->deceased_by);

$this->assertDatabaseHas('nec_activity_logs', [
            'action' => 'voter_deceased',
            'entity_type' => Voter::class,
            'entity_id' => $voter->id,
        ]);
    }

    public function test_death_date_cannot_be_in_the_future()
    {
        $voter = $this->makeVoter();

        $response = $this->from(route('admin.voters.show', $voter->id))->post(route('admin.voters.deceased', $voter->id), [
            'deceased_date' => now()->addDay()->format('Y-m-d'),
        ]);

        $response->assertSessionHasErrors('deceased_date');
        $voter->refresh();
        $this->assertFalse($voter->isDeceased());
    }

    public function test_revive_clears_death_record_and_returns_to_active()
    {
        $voter = $this->makeVoter();
        $voter->markAsDeceased(['deceased_date' => '2026-08-20', 'deceased_by' => 'Test Admin']);

        $response = $this->post(route('admin.voters.revive', $voter->id));

        $response->assertSessionHas('success');
        $voter->refresh();
        $this->assertEquals('active', $voter->status);
        $this->assertNull($voter->deceased_date);
        $this->assertNull($voter->deceased_at);
        $this->assertNull($voter->deceased_by);
        $this->assertNull($voter->death_certificate_ref);
        $this->assertTrue($voter->isEligibleToVote());

$this->assertDatabaseHas('nec_activity_logs', [
            'action' => 'voter_revived',
            'entity_type' => Voter::class,
            'entity_id' => $voter->id,
        ]);
    }

    public function test_deceased_flagged_in_list_stats()
    {
        $this->makeVoter();
        $deceased = $this->makeVoter();
        $deceased->markAsDeceased(['deceased_date' => '2026-06-01']);

        $this->get(route('admin.voters.index'))
            ->assertOk()
            ->assertSee(number_format(1));
    }

    public function test_deceased_stat_ignores_soft_deleted_voters()
    {
        $deceased = $this->makeVoter();
        $deceased->markAsDeceased(['deceased_date' => '2026-06-01']);
        $deceased->delete();

        $this->makeVoter();

        $response = $this->get(route('admin.voters.index'));
        $response->assertOk();
        $this->assertMatchesRegularExpression(
            '/stat-value">0<\/div><div class="stat-label">Deceased/',
            $response->getContent()
        );
    }

    public function test_deceased_stat_counts_voter_deceased_by_status_only()
    {
        $voter = $this->makeVoter();
        $voter->update(['status' => 'deceased']);

        $response = $this->get(route('admin.voters.index'));
        $response->assertOk();
        $this->assertMatchesRegularExpression(
            '/stat-value">1<\/div><div class="stat-label">Deceased/',
            $response->getContent()
        );
    }
}