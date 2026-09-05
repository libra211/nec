<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Voter;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use DatabaseTransactions;

    private function actingAsRole(string $role, string $state = '', string $constituency = ''): void
    {
        $user = User::where('role', $role)->first();
        if (!$user) {
            $user = User::firstOrCreate(
                ['email' => "test_{$role}@nec.test"],
                ['name' => "Test {$role}", 'password' => bcrypt('password'), 'role' => $role, 'state' => $state, 'constituency' => $constituency]
            );
        }

        session([
            'admin_logged_in' => true,
            'admin_user_id' => $user->id,
            'admin_role' => $role,
            'admin_user_name' => $user->name,
            'admin_state' => $state,
            'admin_constituency' => $constituency,
        ]);
    }

    public function test_super_admin_dashboard_renders(): void
    {
        $this->actingAsRole('super_admin');
        $this->get('/admin')->assertStatus(200)->assertSee('stat-value');
    }

    public function test_state_coordinator_dashboard_renders(): void
    {
        $state = Voter::whereNotNull('state')->value('state') ?? 'Central Equatoria';
        $this->actingAsRole('state_coordinator', $state);
        $this->get('/admin')->assertStatus(200)->assertSee('State Voters');
    }

    public function test_constituency_officer_dashboard_renders(): void
    {
        $constituency = Voter::whereNotNull('constituency')->value('constituency') ?? '';
        $this->actingAsRole('constituency_officer', '', $constituency);
        $this->get('/admin')->assertStatus(200)->assertSee('Polling Stations');
    }

    public function test_registration_officer_dashboard_renders(): void
    {
        $this->actingAsRole('registration_officer');
        $this->get('/admin')->assertStatus(200)->assertSee('Registrations Today');
    }

    public function test_polling_officer_dashboard_renders(): void
    {
        $this->actingAsRole('polling_officer');
        $this->get('/admin')->assertStatus(200)->assertSee('Polling Stations');
    }

    public function test_data_entry_dashboard_renders(): void
    {
        $this->actingAsRole('data_entry');
        $this->get('/admin')->assertStatus(200)->assertSee('Registrations Today');
    }

    public function test_media_news_renders_article_images(): void
    {
        $this->get('/media/news')->assertStatus(200)->assertSee('nec-mirror/media', false);
    }
}
