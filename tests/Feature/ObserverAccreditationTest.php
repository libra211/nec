<?php

namespace Tests\Feature;

use App\Models\Country;
use App\Models\ObserverApplication;
use App\Models\ObserverBatch;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ObserverAccreditationTest extends TestCase
{
    use DatabaseTransactions;

    private string $verifyUrl;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        $this->verifyUrl = route('observers.accreditation.verify', 'token');
    }

    private function adminSession(): array
    {
        $user = User::where('role', 'super_admin')->first() ?? User::first();
        return [
            'admin_logged_in' => true,
            'admin_user_id' => $user->id,
            'admin_user_name' => $user->name,
            'admin_role' => 'super_admin',
        ];
    }

    private function applicationData(array $extra = []): array
    {
        $payload = $this->domesticPayload();
        unset($payload['agree_code']);
        $payload['languages'] = is_array($payload['languages']) ? implode(', ', $payload['languages']) : $payload['languages'];

        return array_merge($payload, $extra);
    }

    private function domesticPayload(array $overrides = []): array
    {
        return array_merge([
            'form_type' => 'domestic',
            'title' => 'Mr',
            'first_name' => 'John',
            'last_name' => 'Deng',
            'other_names' => 'Akek',
            'gender' => 'male',
            'dob' => '1988-04-15',
            'nationality' => 'South Sudanese',
            'national_id' => 'SS-DOM-2041',
            'email' => 'john.deng@example.com',
            'phone' => '+211912345678',
            'languages' => ['English', 'Dinka'],
            'organization_name' => 'YMCA South Sudan',
            'agree_code' => '1',
        ], $overrides);
    }

    private function internationalPayload(int $countryId, array $overrides = []): array
    {
        return array_merge([
            'form_type' => 'international',
            'title' => 'Ms',
            'first_name' => 'Amara',
            'last_name' => 'Okafor',
            'gender' => 'female',
            'dob' => '1992-09-02',
            'nationality_id' => $countryId,
            'passport_number' => 'A87654321',
            'email' => 'amara.okafor@example.org',
            'phone' => '+254712345678',
            'languages' => ['English', 'Swahili'],
            'organization_name' => 'EISA',
            'agree_code' => '1',
        ], $overrides);
    }

    public function test_apply_page_renders_both_forms(): void
    {
        $response = $this->get(route('observers.apply'));
        $response->assertOk()->assertSee('Domestic Observer')->assertSee('International Observer');

        $this->get(route('observers.apply', ['type' => 'international']))
            ->assertOk()
            ->assertSee('Passport Number');
    }

    public function test_domestic_application_submits_successfully(): void
    {
        $response = $this->post(route('observers.apply.submit'), $this->domesticPayload());
        $response->assertRedirect();

        $app = ObserverApplication::where('email', 'john.deng@example.com')->first();
        $this->assertNotNull($app);
        $this->assertSame('domestic', $app->form_type);
        $this->assertSame('domestic', $app->observer_type);
        $this->assertSame('South Sudanese', $app->nationality);
        $this->assertSame('Africa', $app->continent);
        $this->assertNotNull($app->verification_token);
        $this->assertSame('pending', $app->status);

        $this->get(route('observers.apply.success', $app->id))
            ->assertOk()
            ->assertSee($app->application_reference)
            ->assertSee('Domestic Observer');
    }

    public function test_domestic_rejects_foreign_nationality_and_phone(): void
    {
        $response = $this->post(route('observers.apply.submit'), $this->domesticPayload([
            'nationality' => 'Kenyan',
        ]));
        $response->assertSessionHasErrors('nationality');

        $response = $this->post(route('observers.apply.submit'), $this->domesticPayload([
            'phone' => '+254712345678',
        ]));
        $response->assertSessionHasErrors('phone');
    }

    public function test_international_application_submits_successfully(): void
    {
        $country = Country::where('status', 'active')->whereNotNull('nationality')->firstOrFail();

        $response = $this->post(route('observers.apply.submit'), $this->internationalPayload($country->id));
        $response->assertRedirect();

        $app = ObserverApplication::where('email', 'amara.okafor@example.org')->first();
        $this->assertNotNull($app);
        $this->assertSame('international', $app->form_type);
        $this->assertSame($country->id, $app->nationality_id);
        $this->assertSame($country->name, $app->nationality);
        $this->assertSame($country->continent, $app->continent);
        $this->assertSame($country->calling_code, $app->country_code);
        $this->assertSame('A87654321', $app->passport_number);
    }

    public function test_international_requires_nationality_and_passport(): void
    {
        $response = $this->post(route('observers.apply.submit'), $this->internationalPayload(
            Country::firstOrFail()->id,
            ['nationality_id' => null, 'passport_number' => null]
        ));
        $response->assertSessionHasErrors(['nationality_id', 'passport_number']);
    }

    public function test_form_type_cannot_be_switched_to_bypass_rules(): void
    {
        // Claiming "international" while submitting domestic-only data fails cleanly.
        $response = $this->post(route('observers.apply.submit'), $this->domesticPayload([
            'form_type' => 'international',
            'passport_number' => null,
            'nationality_id' => null,
        ]));
        $response->assertSessionHasErrors(['nationality_id']);
        $this->assertDatabaseMissing('nec_observer_applications', ['email' => 'john.deng@example.com']);
    }

    public function test_admin_applications_index_and_filters(): void
    {
        ObserverApplication::create($this->applicationData( ['status' => 'pending', 'verification_token' => 't-a']));

        $this->withSession($this->adminSession())
            ->get(route('admin.observers.applications'))
            ->assertOk()
            ->assertSee('john.deng@example.com');
    }

    public function test_admin_approve_generate_and_verify_flow(): void
    {
        $app = ObserverApplication::create($this->applicationData( ['status' => 'pending', 'verification_token' => "token-{$this->randomSuffix()}" ]));

        $this->withSession($this->adminSession())
            ->patch(route('admin.observers.applications.status', $app->id), ['status' => 'approved', 'admin_notes' => 'Docs verified'])
            ->assertRedirect();
        $this->assertSame('approved', $app->fresh()->status);
        $this->assertNotNull($app->fresh()->approved_at);

        $this->withSession($this->adminSession())
            ->post(route('admin.observers.applications.generate', $app->id))
            ->assertRedirect();

        $app->refresh();
        $this->assertNotNull($app->accreditation_number);
        $this->assertStringStartsWith('NEC-OBS', $app->accreditation_number);

        // Public verify page confirms validity.
        $this->get($this->verifyUrl = route('observers.accreditation.verify', $app->verification_token))
            ->assertOk()
            ->assertSee($app->accreditation_number);

        // Badge view renders with the number + QR content.
        $this->withSession($this->adminSession())
            ->get(route('admin.observers.applications.badge', $app->id))
            ->assertOk()
            ->assertSee($app->accreditation_number);
    }

    public function test_verify_page_for_unknown_token_shows_not_found(): void
    {
        $this->get(route('observers.accreditation.verify', 'no-such-token'))
            ->assertOk()
            ->assertSee('Record Not Found');
    }

    public function test_revoked_accreditation_verify_marks_revoked(): void
    {
        $app = ObserverApplication::create($this->applicationData( [
            'status' => 'approved',
            'accreditation_number' => 'NEC-OBS26000001',
            'verification_token' => "token-{$this->randomSuffix()}",
            'revoked_at' => now(),
            'revoked_reason' => 'Credential compromised',
        ]));

        $this->get(route('observers.accreditation.verify', $app->verification_token))
            ->assertOk()
            ->assertSee('Accreditation Revoked')
            ->assertSee('Credential compromised');
    }

    public function test_batch_creation_generation_and_print(): void
    {
        $app = ObserverApplication::create($this->applicationData( ['status' => 'approved', 'verification_token' => "token-{$this->randomSuffix()}" ]));

        $this->withSession($this->adminSession())
            ->post(route('admin.observers.batches.store'), [
                'label' => 'Batch A - October',
                'application_ids' => [$app->id],
            ])
            ->assertRedirect();

        $batch = $app->fresh()->batch;
        $this->assertNotNull($batch);
        $this->assertStringStartsWith('OBB-', $batch->batch_number);
        $this->assertSame($batch->id, $app->fresh()->batch_id);

        $this->withSession($this->adminSession())
            ->post(route('admin.observers.batches.generate', $batch->id))
            ->assertRedirect();
        $this->assertSame('generated', $batch->fresh()->status);
        $this->assertNotNull($app->fresh()->accreditation_number);

        $this->withSession($this->adminSession())
            ->post(route('admin.observers.badge-print'), ['ids' => [$app->id]])
            ->assertOk()
            ->assertSee($app->fresh()->accreditation_number);
    }

    private function randomSuffix(): string
    {
        return substr(bin2hex(random_bytes(8)), 0, 16);
    }
}