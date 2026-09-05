<?php

namespace Tests\Feature;

use App\Helpers\DashboardItems;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class DashboardVisibilityTest extends TestCase
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

    public function test_sections_default_to_visible(): void
    {
        $user = $this->loginAs('state_coordinator', 'coord.ce@nec.gov.ss');
        $response = $this->get('/admin');
        $response->assertStatus(200)
            ->assertSee('Recent Registrations in State')
            ->assertSee('State Voters');
    }

    public function test_superadmin_can_update_visibility_and_dashboard_hides_items(): void
    {
        $enabled = DashboardItems::catalog()['state_coordinator'];

        $this->loginAs('super_admin');
        $payload = [];
        // Disable state_kpis + state_recent, enable everything else.
        foreach ($enabled as $key => $label) {
            $payload['roles']['state_coordinator'][$key] = in_array($key, ['state_kpis', 'state_recent']) ? '0' : '1';
        }

        $this->put('/admin/dashboard-visibility', $payload)
            ->assertStatus(302);

        $this->assertDatabaseHas('dashboard_visibility', [
            'role' => 'state_coordinator',
            'key' => 'state_kpis',
            'visible' => 0,
        ]);
        $this->assertDatabaseHas('dashboard_visibility', [
            'role' => 'state_coordinator',
            'key' => 'state_trend',
            'visible' => 1,
        ]);

        $this->loginAs('state_coordinator', 'coord.ce@nec.gov.ss');
        $response = $this->get('/admin');
        $response->assertStatus(200)
            ->assertDontSee('State Voters')
            ->assertDontSee('Recent Registrations in State');

        $this->assertFalse(DashboardItems::enabled('state_coordinator', 'state_kpis'));
        $this->assertTrue(DashboardItems::enabled('state_coordinator', 'state_trend'));
    }

    public function test_superadmin_can_view_settings_page(): void
    {
        $this->loginAs('super_admin');
        $this->get('/admin/dashboard-visibility')
            ->assertStatus(200)
            ->assertSee('Dashboard Visibility')
            ->assertSee('State Coordinator');
    }

    public function test_non_superadmin_is_blocked_from_visibility_settings(): void
    {
        $this->loginAs('admin', 'itadmin@nec.gov.ss');
        $this->get('/admin/dashboard-visibility')
            ->assertStatus(302);

        $this->loginAs('state_coordinator', 'coord.ce@nec.gov.ss');
        $this->get('/admin/dashboard-visibility')
            ->assertStatus(302);
    }

    public function test_all_state_coordinator_sections_are_catalogued(): void
    {
        $catalog = DashboardItems::catalog();

        foreach ([
            'state_coordinator' => ['state_kpis', 'state_status', 'state_trend', 'state_county', 'state_age', 'state_reg_type', 'state_transfers', 'state_team', 'state_recent'],
            'constituency_officer' => ['constituency_kpis', 'constituency_break', 'constituency_recent'],
            'registration_officer' => ['reg_kpis', 'reg_charts', 'reg_recent', 'reg_actions'],
            'polling_officer' => ['po_kpis', 'po_station_load', 'po_recent_results'],
            'data_entry' => ['de_kpis', 'de_actions', 'de_recent'],
        ] as $role => $keys) {
            $this->assertSame($keys, array_keys($catalog[$role]), "catalog mismatch for {$role}");
        }
    }
}