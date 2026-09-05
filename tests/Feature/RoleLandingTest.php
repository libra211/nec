<?php

namespace Tests\Feature;

use App\Helpers\NecHelper;
use Tests\TestCase;

class RoleLandingTest extends TestCase
{
    public function test_all_staff_roles_share_the_single_admin_dashboard(): void
    {
        foreach (['super_admin', 'admin', 'state_coordinator', 'constituency_officer',
                  'registration_officer', 'polling_officer', 'data_entry', 'content_editor'] as $role) {
            $this->assertSame('admin.dashboard', NecHelper::adminLandingRoute($role), "role {$role} should land on admin.dashboard");
        }
    }

    public function test_voter_observer_land_on_voter_portal(): void
    {
        $this->assertSame('voter.portal.dashboard', NecHelper::adminLandingRoute('voter'));
        $this->assertSame('voter.portal.dashboard', NecHelper::adminLandingRoute('observer'));
    }

    public function test_default_role_lands_on_admin_dashboard(): void
    {
        $this->assertSame('admin.dashboard', NecHelper::adminLandingRoute());
    }
}
