<?php

namespace Tests\Feature;

use App\Models\Country;
use App\Models\DiasporaMission;
use App\Models\Voter;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class VoterRegistrationTest extends TestCase
{
    private function uniqueCode(): string
    {
        do {
            $code = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 2));
        } while (Country::where('code', $code)->exists());
        return $code;
    }

    private function makeCountry(): Country
    {
        $code = $this->uniqueCode();
        return Country::create([
            'name' => 'Test Country ' . uniqid(),
            'code' => $code,
            'iso3' => 'TST',
            'nationality' => 'Testonian',
            'continent' => 'Test',
            'calling_code' => '+1',
            'status' => 'active',
        ]);
    }

    private function makeMission(Country $country): DiasporaMission
    {
        return DiasporaMission::create([
            'country_id' => $country->id,
            'name' => 'Diaspora Mission ' . uniqid(),
            'city' => 'Test City',
            'address' => '123 Test Ave',
            'code' => 'M' . substr(uniqid(), -7),
            'status' => 'active',
        ]);
    }

    private function ssPayload(array $overrides = []): array
    {
        return array_merge([
            'full_name' => 'Test Voter User',
            'gender' => 'M',
            'dob' => '1990-01-15',
            'phone' => '0921234567',
            'email' => 'voter' . uniqid() . '@example.com',
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

    private function diasporaPayload(Country $country, DiasporaMission $mission, array $overrides = []): array
    {
        return array_merge([
            'full_name' => 'Diaspora Voter User',
            'gender' => 'F',
            'dob' => '1985-06-20',
            'phone' => '+14155550100',
            'email' => 'diaspora' . uniqid() . '@example.com',
            'location_type' => 'diaspora',
            'nationality' => 'South Sudanese',
            'preferred_language' => 'English',
            'registration_type' => 'self',
            'passport_number' => 'P-' . uniqid(),
            'country_id' => $country->id,
            'city' => 'New York',
            'address' => '100 Main St',
            'postal_code' => '10001',
            'diaspora_mission_id' => $mission->id,
        ], $overrides);
    }

    public function test_register_page_renders_with_geographic_data()
    {
        $country = $this->makeCountry();
        $this->makeMission($country);

        $response = $this->get(route('voter.register'));
        $response->assertOk();
        $response->assertSee('Voter');
    }

    public function test_ss_registration_validates_and_shows_otp_view()
    {
        $id = (string) uniqid();
        $response = $this->post(route('voter.register.submit'), $this->ssPayload([
            'phone' => '092999' . substr(str_pad($id, 5, '0'), -5),
            'national_id' => 'NID-SS-' . $id,
        ]));

        $response->assertOk();
        $response->assertSee('Verify Your Registration');
        $this->assertNotNull(session('voter_pending'));
        $this->assertFalse(session('voter_pending')['is_diaspora']);
    }

    public function test_diaspora_registration_validates_and_shows_otp_view()
    {
        $country = $this->makeCountry();
        $mission = $this->makeMission($country);
        $id = (string) uniqid();

        $response = $this->post(route('voter.register.submit'), $this->diasporaPayload($country, $mission, [
            'passport_number' => 'PASS-' . $id,
        ]));

        $response->assertOk();
        $this->assertNotNull(session('voter_pending'));
        $this->assertTrue(session('voter_pending')['is_diaspora']);
        $this->assertEquals($country->name, session('voter_pending')['country_name']);
    }

    public function test_ss_registration_rejects_missing_ss_fields()
    {
        $response = $this->post(route('voter.register.submit'), $this->ssPayload([
            'state' => '',
            'payam' => '',
        ]));

        $response->assertSessionHasErrors(['state', 'payam']);
        $this->assertNull(session('voter_pending'));
    }

    public function test_diaspora_registration_rejects_missing_diaspora_fields()
    {
        $country = $this->makeCountry();
        $mission = $this->makeMission($country);

        $response = $this->post(route('voter.register.submit'), $this->diasporaPayload($country, $mission, [
            'passport_number' => '',
            'city' => '',
        ]));

        $response->assertSessionHasErrors(['passport_number', 'city']);
        $this->assertNull(session('voter_pending'));
    }

    public function test_duplicate_phone_blocks_registration()
    {
        Voter::create([
            'voter_id' => 'NEC-V-' . uniqid(),
            'full_name' => 'Existing Voter',
            'phone' => '0991112222',
        ]);
        $id = (string) uniqid();

        $response = $this->post(route('voter.register.submit'), $this->ssPayload([
            'phone' => '0991112222',
            'national_id' => 'NID-DUP-' . $id,
        ]));

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertNull(session('voter_pending'));
    }

    public function test_otp_verification_with_demo_bypass_completes_ss_registration()
    {
        $id = (string) uniqid();

        $this->post(route('voter.register.submit'), $this->ssPayload([
            'phone' => '092777' . substr(str_pad($id, 5, '0'), -5),
            'national_id' => 'NID-OTP-' . $id,
        ]));

        $response = $this->post(route('voter.register.verify-otp'), ['otp' => '000000']);

        $response->assertRedirect(route('voter.registration-success'));
        $voter = Voter::where('national_id', 'NID-OTP-' . $id)->first();
        $this->assertNotNull($voter);
        $this->assertEquals('active', $voter->status);
        $this->assertFalse((bool) $voter->is_diaspora);
        $this->assertEquals('South Sudan', $voter->country_name);
    }

    public function test_otp_verification_with_demo_bypass_completes_diaspora_registration()
    {
        $country = $this->makeCountry();
        $mission = $this->makeMission($country);
        $id = (string) uniqid();

        $this->post(route('voter.register.submit'), $this->diasporaPayload($country, $mission, [
            'passport_number' => 'PASS-OTP-' . $id,
        ]));

        $response = $this->post(route('voter.register.verify-otp'), ['otp' => '000000']);

        $response->assertRedirect(route('voter.registration-success'));
        $voter = Voter::where('passport_number', 'PASS-OTP-' . $id)->first();
        $this->assertNotNull($voter);
        $this->assertTrue((bool) $voter->is_diaspora);
        $this->assertEquals($country->name, $voter->country_name);
        $this->assertEquals($mission->id, $voter->diaspora_mission_id);
    }

    public function test_otp_verification_rejects_wrong_code()
    {
        $id = (string) uniqid();

        $this->post(route('voter.register.submit'), $this->ssPayload([
            'phone' => '092666' . substr(str_pad($id, 5, '0'), -5),
            'national_id' => 'NID-BAD-' . $id,
        ]));

        $response = $this->post(route('voter.register.verify-otp'), ['otp' => '123456']);
        $response->assertOk();
        $response->assertSee('Invalid or expired code');
        $this->assertNull(Voter::where('national_id', 'NID-BAD-' . $id)->first());
    }

    public function test_registration_with_uploads_stores_photos()
    {
        Storage::fake('public');
        $id = (string) uniqid();

        $response = $this->post(route('voter.register.submit'), $this->ssPayload([
            'phone' => '092555' . substr(str_pad($id, 5, '0'), -5),
            'national_id' => 'NID-UP-' . $id,
        ]) + [
            'photo' => UploadedFile::fake()->image('photo.jpg'),
            'id_document' => UploadedFile::fake()->image('id.jpg'),
        ]);

        $response->assertOk();
        $pending = session('voter_pending');
        $this->assertNotNull($pending['photo']);
        $this->assertNotNull($pending['document_photo']);
        Storage::disk('public')->assertExists($pending['photo']);
        Storage::disk('public')->assertExists($pending['document_photo']);
    }

    public function test_check_duplicate_api()
    {
        Voter::create([
            'voter_id' => 'NEC-V-' . uniqid(),
            'full_name' => 'Existing Voter',
            'email' => 'dup@example.com',
        ]);

        $response = $this->postJson(route('api.voter.check-duplicate'), [
            'field' => 'email',
            'value' => 'dup@example.com',
        ]);
        $response->assertOk()->assertJson(['exists' => true]);

        $response = $this->postJson(route('api.voter.check-duplicate'), [
            'field' => 'email',
            'value' => 'fresh@example.com',
        ]);
        $response->assertOk()->assertJson(['exists' => false]);
    }

    public function test_geo_countries_api()
    {
        $country = $this->makeCountry();

        $response = $this->getJson(route('api.geo.countries'));
        $response->assertOk();
        $response->assertJsonFragment(['name' => $country->name]);
    }

    public function test_geo_diaspora_missions_api_filters_by_country()
    {
        $country = $this->makeCountry();
        $mission = $this->makeMission($country);

        $response = $this->getJson(route('api.geo.diaspora-missions', ['country_id' => $country->id]));
        $response->assertOk();
        $response->assertJsonFragment(['name' => $mission->name]);
    }
}
