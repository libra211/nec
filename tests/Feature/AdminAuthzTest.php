<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Voter;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminAuthzTest extends TestCase
{
    use DatabaseTransactions;
    use WithFaker;

    private function makeVoter(): Voter
    {
        return Voter::create([
            'voter_id' => 'VOT-' . strtoupper(uniqid()),
            'full_name' => $this->faker()->name,
            'dob' => '1990-01-01',
            'gender' => 'M',
            'national_id' => (string) random_int(1000000000, 9999999999),
            'phone' => '+211' . random_int(900000000, 999999999),
            'state' => 'Central Equatoria',
            'county' => 'Juba',
            'constituency' => 'Juba County',
            'status' => 'active',
            'registered_at' => '2026-01-01 10:00:00',
            'eligible_to_vote' => true,
        ]);
    }

    private function makeUser(string $role, string $token): User
    {
        return User::create([
            'name' => 'Authz Test User',
            'email' => $this->faker()->unique()->safeEmail,
            'password' => bcrypt('secret12345!'),
            'role' => $role,
            'status' => 'active',
            'remember_token' => $token,
        ]);
    }

    public function test_guest_hitting_admin_route_is_redirected_to_login()
    {
        $this->get(route('admin.voters.index'))
            ->assertRedirect(route('login'))
            ->assertSessionHas('error');
    }

    public function test_viewer_cannot_edit_voters_through_remember_me_cookie()
    {
        $voter = $this->makeVoter();
        $this->makeUser('viewer', 'remember-voter-edit-token');

        $this->withCookie('nec_remember', 'remember-voter-edit-token')
            ->withCookie('nec_remember_type', 'admin')
            ->get(route('admin.voters.edit', $voter->id))
            ->assertRedirect(route('admin.dashboard'))
            ->assertSessionHas('error');
    }

    public function test_super_admin_can_edit_voters_through_remember_me_cookie()
    {
        $voter = $this->makeVoter();
        $this->makeUser('super_admin', 'remember-super-admin-token');

        $this->withCookie('nec_remember', 'remember-super-admin-token')
            ->withCookie('nec_remember_type', 'admin')
            ->get(route('admin.voters.edit', $voter->id))
            ->assertOk();
    }

    public function test_viewer_can_view_voter_list_through_remember_me_cookie()
    {
        $this->makeVoter();
        $this->makeUser('viewer', 'remember-viewer-list-token');

        $this->withCookie('nec_remember', 'remember-viewer-list-token')
            ->withCookie('nec_remember_type', 'admin')
            ->get(route('admin.voters.index'))
            ->assertOk();
    }
}