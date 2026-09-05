<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Worldwide geographic reference: countries
        Schema::create('nec_countries', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120)->unique();
            $table->string('code', 2)->unique();
            $table->string('iso3', 3)->nullable()->index();
            $table->string('nationality', 120)->nullable();
            $table->string('continent', 60)->nullable()->index();
            $table->string('calling_code', 10)->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active')->index();
            $table->timestamps();
        });

        // South Sudanese diplomatic missions / diaspora registration venues abroad
        Schema::create('nec_diaspora_missions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('country_id')->nullable()->index();
            $table->string('name');
            $table->string('city', 120)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('code', 20)->nullable()->unique();
            $table->string('phone', 50)->nullable();
            $table->string('email')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active')->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('nec_voters', function (Blueprint $table) {
            if (!Schema::hasColumn('nec_voters', 'country_id')) {
                $table->unsignedBigInteger('country_id')->nullable()->index()->after('constituency');
            }
            if (!Schema::hasColumn('nec_voters', 'country_name')) {
                $table->string('country_name', 120)->nullable()->after('country_id');
            }
            if (!Schema::hasColumn('nec_voters', 'nationality')) {
                $table->string('nationality', 120)->nullable()->after('country_name');
            }
            if (!Schema::hasColumn('nec_voters', 'city')) {
                $table->string('city', 120)->nullable()->after('boma');
            }
            if (!Schema::hasColumn('nec_voters', 'address')) {
                $table->string('address', 255)->nullable()->after('city');
            }
            if (!Schema::hasColumn('nec_voters', 'postal_code')) {
                $table->string('postal_code', 30)->nullable()->after('address');
            }
            if (!Schema::hasColumn('nec_voters', 'is_diaspora')) {
                $table->boolean('is_diaspora')->default(false)->index()->after('status');
            }
            if (!Schema::hasColumn('nec_voters', 'diaspora_mission_id')) {
                $table->unsignedBigInteger('diaspora_mission_id')->nullable()->index()->after('is_diaspora');
            }
            if (!Schema::hasColumn('nec_voters', 'passport_number')) {
                $table->string('passport_number', 60)->nullable()->index()->after('national_id');
            }
            if (!Schema::hasColumn('nec_voters', 'document_type')) {
                $table->string('document_type', 20)->default('national_id')->after('passport_number');
            }
            if (!Schema::hasColumn('nec_voters', 'document_photo')) {
                $table->string('document_photo', 500)->nullable()->after('email');
            }
            if (!Schema::hasColumn('nec_voters', 'photo')) {
                $table->string('photo', 500)->nullable()->after('document_photo');
            }
            if (!Schema::hasColumn('nec_voters', 'preferred_language')) {
                $table->string('preferred_language', 50)->default('English')->after('photo');
            }
            if (!Schema::hasColumn('nec_voters', 'verified_at')) {
                $table->datetime('verified_at')->nullable()->after('registered_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('nec_voters', function (Blueprint $table) {
            $cols = [
                'country_id', 'country_name', 'nationality', 'city', 'address', 'postal_code',
                'is_diaspora', 'diaspora_mission_id', 'passport_number', 'document_type',
                'document_photo', 'photo', 'preferred_language', 'verified_at',
            ];
            foreach ($cols as $col) {
                if (Schema::hasColumn('nec_voters', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
        Schema::dropIfExists('nec_diaspora_missions');
        Schema::dropIfExists('nec_countries');
    }
};