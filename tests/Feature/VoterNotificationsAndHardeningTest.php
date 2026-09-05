<?php

namespace Tests\Feature;

use App\Models\Notification;
use App\Models\Voter;
use App\Models\VoterAccount;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class VoterNotificationsAndHardeningTest extends TestCase
{
    use DatabaseTransactions;
    use WithFaker;

    private function ssPayload(array $overrides = []): array
    {
        return array_merge([
            'full_name' => 'Notif Voter ' . uniqid(),
            'gender' => 'M',
            'dob' => '1990-01-15',
            'phone' => '092' . random_int(1000000, 9999999),
            'email' => 'notif' . uniqid() . '@example.com',
            'location_type' => 'ss',
            'nationality' => 'South Sudanese',
            'preferred_language' => 'English',
            'registration_type' => 'self',
            'national_id' => 'NID-' . uniqid(),
            'state' => 'Central Equatoria',
            'county' => 'Juba County',
            'constituency' => 'Juba',
            'payam' => 'Juba Town',
            'boma' => 'Kator',
            'polling_station' => 'Juba Primary School',
        ], $overrides);
    }

    private function loginAdmin()
    {
        session([
            'admin_logged_in' => true,
            'admin_user_id' => 1,
            'admin_role' => 'super_admin',
            'admin_user_name' => 'Test Admin',
        ]);
    }

    private function loginVoter(Voter $voter)
    {
        session([
            'voter_logged_in' => true,
            'voter_id' => $voter->voter_id,
            'voter_user_id' => 1,
            'voter_name' => $voter->full_name,
        ]);
    }

    public function test_new_voter_registration_creates_admin_notification()
    {
        $this->post(route('voter.register.submit'), $this->ssPayload());
        $this->post(route('voter.register.verify-otp'), ['otp' => '000000']);

        $latest = Voter::latest('id')->first();

        $this->assertDatabaseHas('nec_notifications', [
            'title' => 'Voter Registration',
            'user_id' => null,
            'type' => 'voter',
        ]);

        $notification = Notification::where('type', 'voter')->latest('id')->first();
        $this->assertStringContainsString($latest->full_name, $notification->message);
        $this->assertStringContainsString('admin/voters', $notification->link);
    }

    public function test_report_issue_creates_admin_notification()
    {
        $this->post(route('voter.report-issue.submit'), [
            'full_name' => 'Complainant Test',
            'email' => 'compl' . uniqid() . '@example.com',
            'category' => 'registration',
            'subject' => 'Issue with my registration',
            'description' => 'I could not complete my registration on the portal.',
        ])->assertSessionHas('success');

        $this->assertDatabaseHas('nec_notifications', [
            'title' => 'Issue Reported',
            'type' => 'complaint',
        ]);
    }

    public function test_admin_bell_displays_notifications_and_new_badges()
    {
        $this->loginAdmin();

        Notification::notifyAdmins('Bell test notification message', [
            'title' => 'Bell Alert',
            'icon' => 'bell',
            'color' => 'primary',
            'link' => route('admin.dashboard'),
        ]);

        $this->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Bell Alert')
            ->assertSee('Bell test notification message')
            ->assertSee('NEW');
    }

    public function test_mark_all_read_route_clears_unread()
    {
        $this->loginAdmin();

        Notification::notifyAdmins('First unread', ['title' => 'One']);
        Notification::notifyAdmins('Second unread', ['title' => 'Two']);

        $this->post(route('admin.notifications.read-all'))
            ->assertSessionHas('success');

        $this->assertSame(0, Notification::unread()->count());
    }

    public function test_mark_read_route_flags_single_notification()
    {
        $this->loginAdmin();

        $notification = Notification::notifyAdmins('Mark this read', ['title' => 'Read Me']);

        $this->post(route('admin.notifications.read', $notification->id))
            ->assertOk()
            ->assertJson(['success' => true]);

        $this->assertNotNull($notification->refresh()->read_at);
    }

    public function test_id_card_renders_voter_and_eligibility_badge()
    {
        $voter = Voter::create([
            'voter_id' => 'VOT-' . strtoupper(uniqid()),
            'full_name' => 'Card Test Citizen',
            'dob' => '1990-01-01',
            'gender' => 'M',
            'national_id' => (string) random_int(1000000000, 9999999999),
            'phone' => '+211' . random_int(900000000, 999999999),
            'email' => 'card' . uniqid() . '@example.com',
            'state' => 'Central Equatoria',
            'county' => 'Juba County',
            'constituency' => 'Juba',
            'polling_station' => 'Juba Primary School',
            'status' => 'active',
            'registered_at' => '2026-01-01 10:00:00',
            'eligible_to_vote' => true,
            'pre_registered' => false,
        ]);

        $this->loginVoter($voter);

        $this->get(route('voter.portal.id-card'))
            ->assertOk()
            ->assertSee('Card Test Citizen')
            ->assertSee($voter->voter_id)
            ->assertSee('Eligible to Vote');
    }

    public function test_id_card_shows_pre_registered_for_minors()
    {
        $voter = Voter::create([
            'voter_id' => 'VOT-' . strtoupper(uniqid()),
            'full_name' => 'Minor Card Citizen',
            'dob' => '2010-01-01',
            'gender' => 'F',
            'national_id' => (string) random_int(1000000000, 9999999999),
            'phone' => '+211' . random_int(900000000, 999999999),
            'email' => 'minor' . uniqid() . '@example.com',
            'state' => 'Central Equatoria',
            'county' => 'Juba County',
            'constituency' => 'Juba',
            'polling_station' => 'Juba Primary School',
            'status' => 'active',
            'registered_at' => '2026-01-01 10:00:00',
            'eligible_to_vote' => false,
            'pre_registered' => true,
            'eligibility_date' => '2028-01-01',
        ]);

        $this->loginVoter($voter);

        $this->get(route('voter.portal.id-card'))
            ->assertOk()
            ->assertSee('Minor Card Citizen')
            ->assertSee('Pre-Registered');
    }

    public function test_voter_registration_endpoint_is_rate_limited()
    {
        $statuses = [];
        for ($i = 0; $i < 35; $i++) {
            $statuses[] = $this->post(route('voter.register.submit'), $this->ssPayload())->status();
        }

        $this->assertContains(429, $statuses, 'Expected a 429 Too Many Requests response from register endpoint.');
    }

    public function test_otp_verification_endpoint_is_rate_limited()
    {
        $statuses = [];
        for ($i = 0; $i < 20; $i++) {
            $statuses[] = $this->post(route('voter.register.verify-otp'), ['otp' => '123456'])->status();
        }

        $this->assertContains(429, $statuses, 'Expected a 429 Too Many Requests response from OTP verify endpoint.');
    }
}