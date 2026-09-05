<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Observer;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AdminPermissionGateTest extends TestCase
{
    use DatabaseTransactions;

    private function loginAs(string $role, string $seedEmail = 'admin@nec.gov.ss'): User
    {
        $user = User::where('email', $seedEmail)->first()
            ?? User::factory()->create(['role' => $role]);
        if ($role && $user->role !== $role && $seedEmail === 'admin@nec.gov.ss') {
            $user->update(['role' => $role]);
        }
        session([
            'admin_logged_in' => true,
            'admin_user_id' => $user->id,
            'admin_user_name' => $user->name,
            'admin_role' => $user->role,
            'admin_state' => $user->state ?? '',
            'admin_constituency' => $user->constituency ?? '',
        ]);
        return $user;
    }

    public function test_superadmin_sees_voter_management_controls(): void
    {
        $this->loginAs('super_admin');
        $this->get('/admin/voters')
            ->assertStatus(200)
            ->assertSee('Register Voter')
            ->assertSee('Import CSV')
            ->assertSee('Bulk Actions');
    }

    public function test_state_coordinator_sees_only_allowed_voter_actions(): void
    {
        $this->loginAs('state_coordinator', 'coord.ce@nec.gov.ss');
        $this->get('/admin/voters')
            ->assertStatus(200)
            ->assertSee('Export CSV')
            ->assertDontSee('Register Voter')
            ->assertDontSee('Import CSV')
            ->assertDontSee('Bulk Actions');
    }

    public function test_viewer_sees_no_voter_management_actions(): void
    {
        $this->loginAs('viewer');
        $this->get('/admin/voters')
            ->assertStatus(200)
            ->assertDontSee('Export CSV')
            ->assertDontSee('Register Voter')
            ->assertDontSee('Bulk Actions');
    }

    public function test_superadmin_only_menu_items_hidden_for_coordinator(): void
    {
        $this->loginAs('state_coordinator', 'coord.ce@nec.gov.ss');
        $this->get('/admin')
            ->assertStatus(200)
            ->assertSee('Voter Transfers')
            ->assertDontSee('Election Petitions')
            ->assertDontSee('Dashboard Visibility')
            ->assertDontSee('Security Logs');
    }

    public function test_roles_without_module_access_are_redirected(): void
    {
        $this->loginAs('state_coordinator', 'coord.ce@nec.gov.ss');
        $this->get('/admin/users')->assertStatus(302);
        $this->get('/admin/agents')->assertStatus(302);
        $this->get('/admin/news')->assertStatus(302);
    }

    public function test_admin_role_keeps_content_and_user_creates_but_not_bulk_delete(): void
    {
        $this->loginAs('admin', 'itadmin@nec.gov.ss');
        $this->get('/admin/news')
            ->assertStatus(200)
            ->assertSee('Add Article');

        $this->get('/admin/users')
            ->assertStatus(200)
            ->assertSee('Add New User')
            ->assertDontSee('Delete Selected');
    }

    public function test_superadmin_still_sees_all_states_filter_on_voters_page(): void
    {
        $this->loginAs('super_admin');
        $this->get('/admin/voters')
            ->assertStatus(200)
            ->assertSee('All States');
    }

    public function test_state_coordinator_voters_page_is_scoped_to_their_state(): void
    {
        $this->loginAs('state_coordinator', 'coord.ee@nec.gov.ss');
        $this->get('/admin/voters')
            ->assertStatus(200)
            ->assertSee('Eastern Equatoria')
            ->assertDontSee('All States');
    }

    public function test_registration_officer_voters_page_is_scoped_to_their_state(): void
    {
        $this->loginAs('registration_officer', 'reg.ee1@nec.gov.ss');
        $this->get('/admin/voters')
            ->assertStatus(200)
            ->assertSee('Eastern Equatoria')
            ->assertDontSee('All States');
    }

    public function test_constituency_officer_without_constituency_is_scoped_to_their_state(): void
    {
        $this->loginAs('constituency_officer', 'const.juba1@nec.gov.ss');
        $this->get('/admin/voters')
            ->assertStatus(200)
            ->assertSee('Central Equatoria')
            ->assertDontSee('All States');
    }

    public function test_observer_page_is_scoped_to_coordinator_state(): void
    {
        Observer::create([
            'email' => 'obs.ee@nec.gov.ss',
            'password' => '098765',
            'last_name' => 'EEObserver',
            'other_names' => 'Betty',
            'category' => 'domestic',
            'assigned_state' => 'Eastern Equatoria',
        ]);
        Observer::create([
            'email' => 'obs.ce@nec.gov.ss',
            'password' => '098765',
            'last_name' => 'CEObserver',
            'other_names' => 'Alpha',
            'category' => 'domestic',
            'assigned_state' => 'Central Equatoria',
        ]);

        $this->loginAs('state_coordinator', 'coord.ee@nec.gov.ss');
        $this->get('/admin/observers')
            ->assertStatus(200)
            ->assertSee('EEObserver')
            ->assertDontSee('CEObserver');
    }

    public function test_superadmin_sees_all_observers_and_can_assign_state(): void
    {
        Observer::create([
            'email' => 'obs.unassigned@nec.gov.ss',
            'password' => '098765',
            'last_name' => 'Unassigned',
            'other_names' => 'Calvin',
            'category' => 'domestic',
        ]);

        $this->loginAs('super_admin');
        $this->get('/admin/observers')
            ->assertStatus(200)
            ->assertSee('Unassigned');

        $target = Observer::where('email', 'obs.unassigned@nec.gov.ss')->firstOrFail();
        $this->patch("/admin/observers/{$target->id}/state", ['assigned_state' => 'Warrap'])
            ->assertStatus(302);

        $this->assertSame('Warrap', $target->fresh()->assigned_state);
    }
}