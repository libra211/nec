<?php

namespace Tests\Feature;

use App\Models\Voter;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Route as RouteFacade;
use Tests\TestCase;

class SiteSmokeTest extends TestCase
{
    use DatabaseTransactions;
    use WithFaker;

    /** @return array<int, Route> */
    private function getRoutes(): array
    {
        return collect(RouteFacade::getRoutes()->getRoutes())
            ->filter(fn (Route $route) => in_array('GET', $route->methods(), true))
            ->values()
            ->all();
    }

    private function isParameterized(Route $route): bool
    {
        return (bool) preg_match('/\{[^}]*\}/', $route->uri());
    }

    private function statusOf(string $uri): int
    {
        $call = fn () => $this->call('GET', $uri);

        try {
            $response = $call();
        } catch (\Throwable $e) {
            return 500;
        }

        return method_exists($response, 'getStatusCode') ? $response->getStatusCode() : 0;
    }

    public function test_all_public_get_routes_render_without_errors()
    {
        $failures = [];

        foreach ($this->getRoutes() as $route) {
            $name = $route->getName() ?? '';
            $uri = $route->uri();

            if ($this->isParameterized($route)) {
                continue;
            }
            if (str_starts_with($name, 'admin.') || str_starts_with($name, 'voter.portal.')) {
                continue;
            }
            if ($uri === 'up' || $uri === 'sandbox' || $name === 'sitemap.xml') {
                continue;
            }

            $status = $this->statusOf($uri);

            if ($status >= 500) {
                $failures[] = "[{$status}] {$uri} ({$name})";
            }
        }

        $this->assertEmpty($failures, "Public routes failed:\n" . implode("\n", $failures));
    }

    public function test_every_admin_index_and_create_page_renders()
    {
        session([
            'admin_logged_in' => true,
            'admin_user_id' => 1,
            'admin_role' => 'super_admin',
            'admin_user_name' => 'Smoke Admin',
        ]);

        $failures = [];

        foreach ($this->getRoutes() as $route) {
            $name = $route->getName() ?? '';

            if (!str_starts_with($name, 'admin.')) {
                continue;
            }
            if ($this->isParameterized($route)) {
                continue;
            }
            if (str_ends_with($name, '.destroy') || str_contains($name, '.reject') || str_contains($name, '.approve')) {
                continue;
            }

            $status = $this->statusOf($route->uri());

            if ($status >= 400) {
                $failures[] = "[{$status}] {$route->uri()} ({$name})";
            }
        }

        $this->assertEmpty($failures, "Admin routes failed:\n" . implode("\n", $failures));
    }

    public function test_every_voter_portal_get_route_renders()
    {
        $voter = Voter::create([
            'voter_id' => 'VOT-SMOKE' . strtoupper(substr(uniqid(), -6)),
            'full_name' => 'Smoke Test Citizen',
            'dob' => '1990-01-01',
            'gender' => 'M',
            'national_id' => (string) random_int(1000000000, 9999999999),
            'phone' => '+211' . random_int(900000000, 999999999),
            'email' => 'smoke' . uniqid() . '@example.com',
            'state' => 'Central Equatoria',
            'county' => 'Juba County',
            'constituency' => 'Juba',
            'polling_station' => 'Smoke Primary School',
            'status' => 'active',
            'registered_at' => '2026-01-01 10:00:00',
            'eligible_to_vote' => true,
            'pre_registered' => false,
        ]);

        session([
            'voter_logged_in' => true,
            'voter_id' => $voter->voter_id,
            'voter_user_id' => 1,
            'voter_name' => $voter->full_name,
        ]);

        $failures = [];

        foreach ($this->getRoutes() as $route) {
            $name = $route->getName() ?? '';

            if (!str_starts_with($name, 'voter.portal.')) {
                continue;
            }
            if ($this->isParameterized($route)) {
                continue;
            }

            $status = $this->statusOf($route->uri());

            if ($status >= 400) {
                $failures[] = "[{$status}] {$route->uri()} ({$name})";
            }
        }

        $this->assertEmpty($failures, "Voter portal routes failed:\n" . implode("\n", $failures));
    }
}