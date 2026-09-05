<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Voter;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class StateDashboardBladeTest extends TestCase
{
    use DatabaseTransactions;

    public function test_state_dashboard_is_state_scoped_and_shows_modules(): void
    {
        $state = Voter::whereNotNull('state')->value('state') ?? 'Central Equatoria';
        $user = User::where('role', 'state_coordinator')->first();
        if (!$user) {
            $this->markTestSkipped('no state_coordinator seeded');
            return;
        }
        session([
            'admin_logged_in' => true,
            'admin_user_id' => $user->id,
            'admin_role' => 'state_coordinator',
            'admin_user_name' => $user->name,
            'admin_state' => $state,
            'admin_constituency' => '',
        ]);

        $response = $this->get('/admin');
        $response->assertStatus(200)
            ->assertSee('State Modules')
            ->assertSee('Polling Stations')
            ->assertDontSee('Pending Transfer')
            ->assertSee('Recent Registrations in State')
            ->assertSee('Age Distribution')
            ->assertSee('Registration Type');
    }
}