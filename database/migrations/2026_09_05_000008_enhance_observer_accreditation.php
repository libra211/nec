<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('nec_observer_batches')) {
            Schema::create('nec_observer_batches', function (Blueprint $table) {
                $table->id();
                $table->string('batch_number', 50)->unique();
                $table->string('label', 255)->nullable();
                $table->enum('status', ['draft', 'generated', 'closed'])->default('draft')->index();
                $table->text('notes')->nullable();
                $table->unsignedBigInteger('generated_by')->nullable();
                $table->datetime('generated_at')->nullable();
                $table->timestamps();
            });
        }

        Schema::table('nec_observer_applications', function (Blueprint $table) {
            if (!Schema::hasColumn('nec_observer_applications', 'form_type')) {
                $table->enum('form_type', ['domestic', 'international'])->nullable()->default('domestic')->after('observer_type');
            }
            if (!Schema::hasColumn('nec_observer_applications', 'nationality_id')) {
                $table->unsignedBigInteger('nationality_id')->nullable()->after('nationality');
            }
            if (!Schema::hasColumn('nec_observer_applications', 'continent')) {
                $table->string('continent', 60)->nullable()->after('nationality_id');
            }
            if (!Schema::hasColumn('nec_observer_applications', 'passport_number')) {
                $table->string('passport_number', 100)->nullable()->after('national_id');
            }
            if (!Schema::hasColumn('nec_observer_applications', 'country_code')) {
                $table->string('country_code', 10)->nullable()->after('phone');
            }
            if (!Schema::hasColumn('nec_observer_applications', 'accreditation_number')) {
                $table->string('accreditation_number', 50)->nullable()->unique()->after('status');
            }
            if (!Schema::hasColumn('nec_observer_applications', 'verification_token')) {
                $table->string('verification_token', 100)->nullable()->unique()->after('accreditation_number');
            }
            if (!Schema::hasColumn('nec_observer_applications', 'batch_id')) {
                $table->unsignedBigInteger('batch_id')->nullable()->after('verification_token');
            }
            if (!Schema::hasColumn('nec_observer_applications', 'approved_at')) {
                $table->datetime('approved_at')->nullable()->after('admin_notes');
            }
            if (!Schema::hasColumn('nec_observer_applications', 'approved_by')) {
                $table->unsignedBigInteger('approved_by')->nullable()->after('approved_at');
            }
            if (!Schema::hasColumn('nec_observer_applications', 'revoked_at')) {
                $table->datetime('revoked_at')->nullable()->after('approved_by');
            }
            if (!Schema::hasColumn('nec_observer_applications', 'revoked_by')) {
                $table->unsignedBigInteger('revoked_by')->nullable()->after('revoked_at');
            }
            if (!Schema::hasColumn('nec_observer_applications', 'revoked_reason')) {
                $table->text('revoked_reason')->nullable()->after('revoked_by');
            }

            $table->index('batch_id', 'nec_observer_applications_batch_id_index');
            $table->index('form_type', 'nec_observer_applications_form_type_index');
        });
    }

    public function down()
    {
        Schema::table('nec_observer_applications', function (Blueprint $table) {
            $table->dropIndex('nec_observer_applications_batch_id_index');
            $table->dropIndex('nec_observer_applications_form_type_index');
            $table->dropUnique(['accreditation_number']);
            $table->dropUnique(['verification_token']);

            foreach ([
                'form_type', 'nationality_id', 'continent', 'passport_number', 'country_code',
                'accreditation_number', 'verification_token', 'batch_id', 'approved_at',
                'approved_by', 'revoked_at', 'revoked_by', 'revoked_reason',
            ] as $column) {
                if (Schema::hasColumn('nec_observer_applications', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::dropIfExists('nec_observer_batches');
    }
};