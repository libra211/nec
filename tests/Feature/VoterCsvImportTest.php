<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Voter;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class VoterCsvImportTest extends TestCase
{
    use DatabaseTransactions;

    private function authAsSuperAdmin(): void
    {
        $user = User::where('role', 'super_admin')->first()
            ?? User::create([
                'name' => 'Import Tester',
                'email' => 'importer@nec.test',
                'password' => bcrypt('password'),
                'role' => 'super_admin',
                'status' => 'active',
            ]);

        session([
            'admin_logged_in' => true,
            'admin_user_id' => $user->id,
            'admin_role' => $user->role,
            'admin_user_name' => $user->name,
        ]);
    }

    private function csvUpload(array $rows, bool $withHeader = true): UploadedFile
    {
        $header = 'voter_id,full_name,gender,dob,national_id,phone,email,payam,boma,polling_station,registration_center';
        $content = $withHeader ? $header . "\n" : '';
        foreach ($rows as $row) {
            $content .= implode(',', $row) . "\n";
        }
        return UploadedFile::fake()->createWithContent('voters.csv', $content);
    }

    public function test_import_creates_voters_scoped_to_chosen_area(): void
    {
        $this->authAsSuperAdmin();

        $before = Voter::query()->count();

        $response = $this->post(route('admin.voters.import'), [
            'csv_file' => $this->csvUpload([
                ['', 'Deng Akech', 'M', '1990-01-15', 'NID-IMP-001', '+211912000001', 'imp1@example.com', 'Juba Town', 'Kator', 'Juba Primary', 'Juba Center'],
                ['', 'Awut Nyok', 'F', '1988-03-22', 'NID-IMP-002', '+211912000002', 'imp2@example.com', 'Juba Town', 'Munuki', 'Munuki Primary', 'Juba Center'],
            ]),
            'import_state' => 'Central Equatoria',
            'import_county' => 'Juba County',
            'import_constituency' => 'Juba',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $created = Voter::query()->whereIn('national_id', ['NID-IMP-001', 'NID-IMP-002'])->get();
        $this->assertCount(2, $created);
        $this->assertEquals($before + 2, Voter::query()->count());
        foreach ($created as $voter) {
            $this->assertEquals('Central Equatoria', $voter->state);
            $this->assertEquals('Juba County', $voter->county);
            $this->assertEquals('Juba', $voter->constituency);
            $this->assertEquals('agent', $voter->registration_type);
            $this->assertEquals('Bulk Import', $voter->registered_by_name);
            $this->assertNotNull($voter->voter_id);
            $this->assertTrue((bool) $voter->eligible_to_vote);
        }
    }

    public function test_import_skips_duplicates_and_reports_counts(): void
    {
        $this->authAsSuperAdmin();

        Voter::create([
            'voter_id' => 'NEC26M900001',
            'full_name' => 'Existing Voter',
            'phone' => '+211912000099',
        ]);

        $response = $this->post(route('admin.voters.import'), [
            'csv_file' => $this->csvUpload([
                ['', 'New Voter', 'M', '1990-01-15', 'NID-IMP-101', '+211912000101', null, null, null, null, null],
                ['', 'Same Phone', 'F', '1992-06-06', 'NID-IMP-102', '+211912000099', null, null, null, null, null],
                ['', 'Bad Row', 'X', 'not-a-date', 'NID-IMP-103', '+211912000103', null, null, null, null, null],
            ]),
            'import_state' => 'Central Equatoria',
        ]);

        $response->assertSessionHas('import_summary', fn ($summary) =>
            $summary['imported'] === 1 && $summary['duplicates'] === 1 && $summary['invalid'] === 1);

        $this->assertNotNull(Voter::where('national_id', 'NID-IMP-101')->first());
        $this->assertNull(Voter::where('national_id', 'NID-IMP-102')->first());
        $this->assertNull(Voter::where('national_id', 'NID-IMP-103')->first());
    }

    public function test_import_rejects_unknown_state(): void
    {
        $this->authAsSuperAdmin();

        $response = $this->post(route('admin.voters.import'), [
            'csv_file' => $this->csvUpload([['', 'Fake Voter', 'M', '1990-01-15', 'NID-IMP-201', '+211912000201', null, null, null, null, null]]),
            'import_state' => 'Not A Real State',
        ]);

        $response->assertSessionHas('error');
        $this->assertNull(Voter::where('national_id', 'NID-IMP-201')->first());
    }

    public function test_import_requires_valid_file(): void
    {
        $this->authAsSuperAdmin();

        $response = $this->post(route('admin.voters.import'), [
            'csv_file' => UploadedFile::fake()->image('not-a-csv.jpg'),
            'import_state' => 'Central Equatoria',
        ]);

        $response->assertSessionHasErrors('csv_file');
    }

    public function test_import_template_downloads(): void
    {
        $this->authAsSuperAdmin();

        $response = $this->get(route('admin.voters.import-template'));
        $response->assertOk();
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        $this->assertStringContainsString('full_name', $response->streamedContent());
    }
}