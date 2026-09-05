<?php

namespace Database\Seeders;

use App\Helpers\NecHelper;
use App\Models\Country;
use App\Models\ObserverApplication;
use App\Models\ObserverBatch;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ObserverApplicationSeeder extends Seeder
{
    public function run(): void
    {
        $storage = Storage::disk('public');

        if (! $this->alreadySeeded('OBB-DEMO-2026-1')) {
            $applications = $this->baseApplications();

            foreach ($applications as $data) {
                $email = $data['email'];

                $record = ObserverApplication::where('email', $email)->first();

                if ($record) {
                    $record->update($data);
                } else {
                    $data['verification_token'] = Str::random(40);
                    $record = ObserverApplication::create($data);
                }

                $this->attachUploads($record, $storage);
                $record->save();
            }

            $approved = ObserverApplication::where('status', 'approved')
                ->whereNull('batch_id')
                ->whereNull('revoked_at')
                ->get();

            $batch = ObserverBatch::create([
                'batch_number' => 'OBB-DEMO-2026-1',
                'label' => 'Batch 1 - October Missions',
                'notes' => 'Demo batch: domestic and international observers approved for the 2026 general elections.',
                'generated_by' => $this->adminId(),
                'generated_at' => now(),
            ]);

            $counter = 0;
            foreach ($approved as $app) {
                $app->update([
                    'batch_id' => $batch->id,
                    'accreditation_number' => NecHelper::generate_accreditation_number(),
                    'verification_token' => $app->verification_token ?: Str::random(40),
                    'status' => 'approved',
                    'approved_at' => now()->subDay(),
                    'approved_by' => $this->adminId(),
                ]);
                $counter++;
            }

            if ($counter > 0) {
                $batch->update(['status' => 'generated']);
            }

            // Leave one approved application out of any batch so admins can
            // see the "assign to batch" workflow live.
            ObserverApplication::where('status', 'approved')
                ->whereNotNull('accreditation_number')
                ->whereNull('revoked_at')
                ->latest()
                ->first()
                ?->update(['batch_id' => null]);

            $this->command->info('ObserverApplicationSeeder: seeded ' . $approved->count() . ' accredited observer(s) in ' . $batch->batch_number);
        } else {
            $this->command->info('ObserverApplicationSeeder: demo batch already exists; skipped.');
        }
    }

    private function alreadySeeded(string $batchNumber): bool
    {
        return ObserverBatch::where('batch_number', $batchNumber)->exists();
    }

    private function adminId(): ?int
    {
        return User::where('role', 'super_admin')->value('id');
    }

    private function baseApplications(): array
    {
        $africanCountry = fn () => Country::whereIn('continent', ['Africa'])
            ->inRandomOrder()->first();
        $british = Country::where('name', 'LIKE', '%United Kingdom%')->orWhere('nationality', 'LIKE', '%British%')->first();
        $uganda = Country::where('name', 'LIKE', '%Uganda%')->first();
        $nigeria = Country::where('name', 'LIKE', '%Nigeria%')->orWhere('nationality', 'LIKE', '%Nigerian%')->first();
        $c = $africanCountry();

        return [
            // ---- Domestic observers ----
            [
                'title' => 'Rev.', 'first_name' => 'Peter', 'last_name' => 'Wol', 'other_names' => 'Lam',
                'gender' => 'male', 'dob' => '1972-03-14', 'form_type' => 'domestic', 'observer_type' => 'domestic',
                'nationality' => 'South Sudanese', 'nationality_id' => null, 'continent' => 'Africa',
                'national_id' => 'SSN-1001-887', 'email' => 'peter.wol@sscc.org', 'phone' => '+211912300001',
                'languages' => 'English, Dinka, Arabic (Juba Arabic)',
                'residential_address' => 'Kololo Road, Juba, Central Equatoria',
                'organization_name' => 'South Sudan Council of Churches', 'status' => 'approved',
                'created_at' => now()->subDays(6),
            ],
            [
                'title' => 'Ms.', 'first_name' => 'Victoria', 'last_name' => 'Adut', 'other_names' => 'Akol',
                'gender' => 'female', 'dob' => '1985-07-22', 'form_type' => 'domestic', 'observer_type' => 'domestic',
                'nationality' => 'South Sudanese', 'nationality_id' => null, 'continent' => 'Africa',
                'national_id' => 'SSN-1002-221', 'email' => 'v.adut@sswco.org', 'phone' => '+211912300002',
                'languages' => 'English, Bari, Juba Arabic',
                'residential_address' => 'Malakal Road, Juba', 'organization_name' => "South Sudan Women's Coalition",
                'status' => 'approved', 'created_at' => now()->subDays(5),
            ],
            [
                'title' => 'Mr.', 'first_name' => 'Emmanuel', 'last_name' => 'Monyluak', 'other_names' => 'Deng',
                'gender' => 'male', 'dob' => '1990-11-03', 'form_type' => 'domestic', 'observer_type' => 'domestic',
                'nationality' => 'South Sudanese', 'nationality_id' => null, 'continent' => 'Africa',
                'national_id' => 'SSN-1003-545', 'email' => 'e.monyluak@cepo.org', 'phone' => '+211912300003',
                'languages' => 'English, Dinka',
                'residential_address' => 'Hai Malakal, Juba', 'organization_name' => 'Community Empowerment for Progress Organization',
                'status' => 'approved', 'created_at' => now()->subDays(4),
            ],
            [
                'title' => 'Ms.', 'first_name' => 'Rachel', 'last_name' => 'Nyaakol', 'other_names' => 'Wau',
                'gender' => 'female', 'dob' => '1997-01-18', 'form_type' => 'domestic', 'observer_type' => 'domestic',
                'nationality' => 'South Sudanese', 'nationality_id' => null, 'continent' => 'Africa',
                'national_id' => 'SSN-1004-009', 'email' => 'r.nyaakol@ssyu.org', 'phone' => '+211912300004',
                'languages' => 'English, Shilluk',
                'residential_address' => 'Hai Cinema, Juba', 'organization_name' => 'South Sudan Youth Union',
                'status' => 'reviewing', 'created_at' => now()->subDays(3),
            ],
            [
                'title' => 'Mr.', 'first_name' => 'Deng', 'last_name' => 'Kuol', 'other_names' => 'Bol',
                'gender' => 'male', 'dob' => '1980-09-29', 'form_type' => 'domestic', 'observer_type' => 'domestic',
                'nationality' => 'South Sudanese', 'nationality_id' => null, 'continent' => 'Africa',
                'national_id' => 'SSN-1005-673', 'email' => 'd.bol@sscsa.org', 'phone' => '+211912300008',
                'languages' => 'English, Nuer',
                'residential_address' => 'Gudele II, Juba', 'organization_name' => 'South Sudan Civil Society Alliance',
                'status' => 'pending', 'created_at' => now()->subDays(1),
            ],
            [
                'title' => 'Mrs.', 'first_name' => 'Nyamal', 'last_name' => 'Peter', 'other_names' => 'Atak',
                'gender' => 'female', 'dob' => '1978-05-12', 'form_type' => 'domestic', 'observer_type' => 'domestic',
                'nationality' => 'South Sudanese', 'nationality_id' => null, 'continent' => 'Africa',
                'national_id' => 'SSN-1006-331', 'email' => 'n.peter@ssdn.org', 'phone' => '+211912300009',
                'languages' => 'English, Arabic (Juba Arabic)',
                'residential_address' => 'Jebel Market, Juba', 'organization_name' => 'South Sudanese Diaspora Network',
                'status' => 'pending', 'created_at' => now()->subHours(8),
            ],

            // ---- International observers ----
            [
                'title' => 'Amb.', 'first_name' => 'Amina', 'last_name' => 'Ali', 'other_names' => 'Osman',
                'gender' => 'female', 'dob' => '1968-02-09', 'form_type' => 'international', 'observer_type' => 'international',
                'nationality' => 'Kenyan', 'nationality_id' => Country::where('nationality', 'LIKE', '%Kenyan%')->value('id'),
                'continent' => 'Africa', 'country_code' => '+254', 'passport_number' => 'KN-OL-889123',
                'email' => 'amina.ali@au.int', 'phone' => '+254700100200',
                'languages' => 'English, Swahili',
                'residential_address' => 'Nairobi, Kenya', 'organization_name' => 'African Union Election Observation Mission',
                'status' => 'approved', 'created_at' => now()->subDays(7),
            ],
            [
                'title' => 'Dr.', 'first_name' => 'Jean-Pierre', 'last_name' => 'Mbarga', 'other_names' => null,
                'gender' => 'male', 'dob' => '1971-10-30', 'form_type' => 'international', 'observer_type' => 'international',
                'nationality' => 'Togolese', 'nationality_id' => Country::where('nationality', 'LIKE', '%Togolese%')->value('id'),
                'continent' => 'Africa', 'country_code' => '+228', 'passport_number' => 'TG-MB-450112',
                'email' => 'jp.mbarga@ecowas.int', 'phone' => '+22890123456',
                'languages' => 'English, French',
                'residential_address' => 'Lomé, Togo', 'organization_name' => 'ECOWAS Election Observation Group',
                'status' => 'approved', 'created_at' => now()->subDays(7),
            ],
            [
                'title' => 'Ms.', 'first_name' => 'Sarah', 'last_name' => 'Mitchell', 'other_names' => 'Crane',
                'gender' => 'female', 'dob' => '1983-06-05', 'form_type' => 'international', 'observer_type' => 'international',
                'nationality' => 'British', 'nationality_id' => $british?->id,
                'continent' => $british?->continent ?? 'Europe', 'country_code' => '+44', 'passport_number' => 'GB-MT-778201',
                'email' => 's.mitchell@eeas.europa.eu', 'phone' => '+32470123456',
                'languages' => 'English, French, German',
                'residential_address' => 'Brussels, Belgium', 'organization_name' => 'European Union Election Observation Mission',
                'status' => 'approved', 'created_at' => now()->subDays(6),
            ],
            [
                'title' => 'Prof.', 'first_name' => 'James', 'last_name' => 'Okello', 'other_names' => null,
                'gender' => 'male', 'dob' => '1965-12-19', 'form_type' => 'international', 'observer_type' => 'international',
                'nationality' => 'Ugandan', 'nationality_id' => $uganda?->id,
                'continent' => $uganda?->continent ?? 'Africa', 'country_code' => '+256', 'passport_number' => 'UG-OK-556812',
                'email' => 'j.okello@eac.int', 'phone' => '+256701234567',
                'languages' => 'English, Swahili, Luganda',
                'residential_address' => 'Kampala, Uganda', 'organization_name' => 'East African Community Observer Mission',
                'status' => 'approved', 'created_at' => now()->subDays(6),
            ],
            [
                'title' => 'Ms.', 'first_name' => 'Caroline', 'last_name' => 'Achieng', 'other_names' => null,
                'gender' => 'female', 'dob' => '1991-08-27', 'form_type' => 'international', 'observer_type' => 'international',
                'nationality' => $c?->nationality ?? 'Ethiopian', 'nationality_id' => $c?->id,
                'continent' => $c?->continent ?? 'Africa', 'country_code' => $c?->calling_code ?? '+251',
                'passport_number' => 'ET-AC-903224',
                'email' => 'c.achieng@au.int', 'phone' => ($c?->calling_code ?? '+251') . '112345678',
                'languages' => 'English, Swahili',
                'residential_address' => 'Addis Ababa, Ethiopia', 'organization_name' => 'African Union Peace and Security Council',
                'status' => 'approved', 'created_at' => now()->subDays(5),
            ],
            [
                'title' => 'Mr.', 'first_name' => 'Thomas', 'last_name' => 'Boni', 'other_names' => 'Harvey',
                'gender' => 'male', 'dob' => '1975-04-11', 'form_type' => 'international', 'observer_type' => 'international',
                'nationality' => 'American', 'nationality_id' => Country::where('nationality', 'LIKE', '%American%')->value('id'),
                'continent' => 'North America', 'country_code' => '+1', 'passport_number' => 'US-BN-118904',
                'email' => 't.boni@cartercenter.org', 'phone' => '+14045551234',
                'languages' => 'English, Spanish',
                'residential_address' => 'Atlanta, Georgia, USA', 'organization_name' => 'Carter Center Election Observation Program',
                'status' => 'pending', 'created_at' => now()->subDays(2),
            ],
            [
                'title' => 'Mr.', 'first_name' => 'Ahmed', 'last_name' => 'Nur', 'other_names' => 'Ibrahim',
                'gender' => 'male', 'dob' => '1987-02-16', 'form_type' => 'international', 'observer_type' => 'international',
                'nationality' => 'Somali', 'nationality_id' => Country::where('nationality', 'LIKE', '%Somali%')->value('id'),
                'continent' => 'Africa', 'country_code' => '+252', 'passport_number' => 'SO-NR-245173',
                'email' => 'a.nur@ifes.org', 'phone' => '+252611234567',
                'languages' => 'English, Somali, Arabic',
                'residential_address' => 'Mogadishu, Somalia', 'organization_name' => 'International Foundation for Electoral Systems',
                'status' => 'pending', 'created_at' => now()->subHours(30),
            ],
        ];
    }

    private function attachUploads(ObserverApplication $app, $storage): void
    {
        $base = 'observer_uploads';

        if (! $app->passport_photo) {
            $photoPath = "{$base}/photos/{$app->id}.png";
            $storage->put($photoPath, $this->makePhoto($app->full_name));
            $app->passport_photo = $photoPath;
        }

        $docs = [
            'cv_biography' => "CURRICULUM VITAE\n\n{$app->full_name}\n{$app->email}\n{$app->phone}\n\nElection observation experience and professional background.",
            'letter_of_appointment' => "LETTER OF APPOINTMENT\n\nThis is to confirm that {$app->full_name} has been appointed as an observer for the 2026 general elections under {$app->organization_name}.\n\nSigned, Head of Mission.",
            'proof_registration' => "PROOF OF REGISTRATION\n\nRegistration reference: {$app->application_reference} \nOrganization registration on file with the National Election Commission.",
        ];

        foreach ($docs as $field => $content) {
            if (! $app->$field) {
                $path = "{$base}/" . ['cv_biography' => 'cv', 'letter_of_appointment' => 'letters', 'proof_registration' => 'proofs'][$field] . "/{$app->id}-{$field}.txt";
                $storage->put($path, $content);
                $app->$field = $path;
            }
        }
    }

    private function makePhoto(string $name): string
    {
        $w = 240;
        $h = 300;
        $img = imagecreatetruecolor($w, $h);
        $bg = imagecolorallocate($img, 226, 240, 232);
        $fg = imagecolorallocate($img, 22, 101, 52);
        $dark = imagecolorallocate($img, 30, 41, 59);
        imagefilledrectangle($img, 0, 0, $w, $h, $bg);
        imagefilledrectangle($img, 0, 0, $w, 22, $fg);

        $short = collect(explode(' ', trim($name)))->take(2)->implode(' ');
        imagestring($img, 5, 22, ($h / 2) - 12, substr($short, 0, 24), $dark);
        imagestring($img, 3, 22, ($h / 2) + 12, 'Observer ID photo', imagecolorallocate($img, 100, 116, 139));

        ob_start();
        imagepng($img);
        $png = ob_get_clean();
        imagedestroy($img);

        return $png;
    }
}