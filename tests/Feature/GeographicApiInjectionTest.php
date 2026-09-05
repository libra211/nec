<?php

namespace Tests\Feature;

use Tests\TestCase;

class GeographicApiInjectionTest extends TestCase
{
    private function existingStateCode(): ?string
    {
        return \App\Models\State::where('status', 'active')->value('code');
    }

    public function test_constituencies_by_state_code_still_work()
    {
        $code = $this->existingStateCode();
        if (!$code) {
            $this->markTestSkipped('No active states seeded');
        }

        $response = $this->getJson(route('api.geo.constituencies', ['state' => $code]));
        $response->assertOk();
        $this->assertIsArray($response->json());
    }

    public function test_polling_stations_by_state_code_still_work()
    {
        $code = $this->existingStateCode();
        if (!$code) {
            $this->markTestSkipped('No active states seeded');
        }

        $response = $this->getJson(route('api.geo.polling-stations', ['state' => $code]));
        $response->assertOk();
        $this->assertArrayHasKey('stations', $response->json());
    }

    public function test_injection_attempt_is_neutralized_in_constituencies()
    {
        $injection = "' OR '1'='1";

        $response = $this->getJson(route('api.geo.constituencies', ['state' => $injection]));
        $response->assertOk();
        $this->assertSame([], $response->json());
    }

    public function test_injection_attempt_is_neutralized_in_polling_stations()
    {
        $injection = "1 OR 1=1 -- ";

        $response = $this->getJson(route('api.geo.polling-stations', ['state' => $injection]));
        $response->assertOk();
        $this->assertSame([], $response->json()['stations']);
    }
}