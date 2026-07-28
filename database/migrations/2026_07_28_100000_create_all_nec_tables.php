<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        Schema::create('nec_regions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->unsignedInteger('sort_order')->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');
        });

        Schema::create('nec_states', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('region_id')->nullable()->index();
            $table->string('name', 100)->unique();
            $table->string('capital', 100)->nullable();
            $table->string('code', 10)->nullable();
            $table->unsignedInteger('constituencies')->default(0);
            $table->unsignedBigInteger('estimated_population')->default(0);
            $table->unsignedBigInteger('avg_voters')->default(0);
            $table->enum('status', ['active', 'inactive', 'trash'])->default('active');
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('nec_counties', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedInteger('state_id')->nullable()->index();
            $table->enum('status', ['active', 'inactive'])->default('active');
        });

        Schema::create('nec_constituencies', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('name');
            $table->string('state', 100)->nullable()->index();
            $table->string('county', 100)->nullable();
            $table->unsignedInteger('county_id')->nullable()->index();
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'inactive', 'trash'])->default('active');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('nec_payams', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('county_id');
            $table->enum('status', ['active', 'inactive', 'trash'])->default('active');
            $table->timestamps();
        });

        Schema::create('nec_bomas', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('payam_id');
            $table->enum('status', ['active', 'inactive', 'trash'])->default('active');
            $table->timestamps();
        });

        Schema::create('nec_polling_stations', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->string('code', 50)->nullable();
            $table->string('state', 100)->nullable()->index();
            $table->string('county', 100)->nullable();
            $table->string('constituency', 100)->nullable()->index();
            $table->string('payam', 100)->nullable();
            $table->unsignedInteger('registered_voters')->default(0);
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->enum('status', ['active', 'inactive', 'trash'])->default('active');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('nec_users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('phone', 50)->nullable();
            $table->string('password');
            $table->string('name');
            $table->string('photo', 500)->nullable();
            $table->string('avatar', 500)->nullable();
            $table->enum('role', ['super_admin','admin','state_coordinator','constituency_officer','registration_officer','polling_officer','data_entry','content_editor','viewer'])->default('content_editor')->index();
            $table->string('department', 100)->nullable();
            $table->string('state', 100)->nullable();
            $table->string('position')->nullable();
            $table->string('employee_id', 50)->nullable();
            $table->enum('status', ['active', 'inactive', 'trash'])->default('active')->index();
            $table->boolean('two_factor_enabled')->default(false);
            $table->text('notes')->nullable();
            $table->datetime('last_login')->nullable();
            $table->datetime('last_active')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('nec_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 100)->unique();
            $table->string('name', 100);
            $table->string('module', 50)->index();
            $table->string('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('nec_role_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('role', 50)->index();
            $table->unsignedBigInteger('permission_id')->index();
            $table->timestamps();
        });

        Schema::create('nec_voters', function (Blueprint $table) {
            $table->id();
            $table->string('voter_id', 50)->unique();
            $table->string('national_id', 50)->nullable()->index();
            $table->string('reg_number', 50)->nullable();
            $table->string('full_name')->index();
            $table->date('dob')->nullable();
            $table->enum('gender', ['M', 'F', 'Other'])->nullable();
            $table->string('phone', 50)->nullable()->index();
            $table->string('email')->nullable();
            $table->string('state', 100)->nullable()->index();
            $table->string('county', 100)->nullable();
            $table->string('constituency', 100)->nullable()->index();
            $table->string('payam', 100)->nullable();
            $table->string('boma', 100)->nullable();
            $table->string('polling_station')->nullable();
            $table->string('registration_center')->nullable();
            $table->enum('status', ['active', 'inactive', 'suspended', 'trash'])->default('active')->index();
            $table->enum('registration_type', ['self', 'agent'])->nullable();
            $table->string('registered_by_name')->nullable();
            $table->string('registered_by_title')->nullable();
            $table->string('registered_by_location')->nullable();
            $table->unsignedBigInteger('registered_by_user_id')->nullable();
            $table->datetime('registered_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('nec_voter_accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('voter_id')->index();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('pin_code', 10)->nullable();
            $table->datetime('email_verified_at')->nullable();
            $table->datetime('last_login')->nullable();
            $table->integer('login_attempts')->default(0);
            $table->datetime('locked_until')->nullable();
            $table->enum('status', ['active', 'inactive', 'locked'])->default('active')->index();
            $table->timestamps();
        });

        Schema::create('nec_voter_transfers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('voter_id')->nullable();
            $table->string('voter_identifier', 50)->index();
            $table->string('full_name');
            $table->string('from_state', 100);
            $table->string('from_constituency', 100);
            $table->string('to_state', 100);
            $table->string('to_constituency', 100);
            $table->text('reason')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled'])->default('pending')->index();
            $table->string('reviewed_by')->nullable();
            $table->datetime('reviewed_at')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });

        Schema::create('nec_agents', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone', 50)->unique();
            $table->string('email')->nullable()->unique();
            $table->string('national_id', 50)->nullable();
            $table->string('title')->nullable();
            $table->string('state', 100)->index();
            $table->string('county', 100)->nullable();
            $table->string('constituency')->nullable();
            $table->string('payam', 100)->nullable();
            $table->string('boma', 100)->nullable();
            $table->string('assigned_state', 100)->nullable();
            $table->string('assigned_county', 100)->nullable();
            $table->string('assigned_constituency')->nullable();
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active')->index();
            $table->unsignedInteger('voters_registered')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('nec_political_parties', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->string('acronym', 50)->nullable();
            $table->string('leader')->nullable();
            $table->year('founded')->nullable();
            $table->string('logo', 500)->nullable();
            $table->string('color', 7)->nullable();
            $table->enum('status', ['active', 'inactive', 'trash'])->default('active')->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('nec_candidates', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->unsignedInteger('party_id')->nullable()->index();
            $table->string('position');
            $table->string('constituency')->nullable();
            $table->string('state', 100)->nullable();
            $table->string('photo', 500)->nullable();
            $table->text('bio')->nullable();
            $table->enum('status', ['active', 'inactive', 'trash'])->default('active')->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('nec_results', function (Blueprint $table) {
            $table->id();
            $table->string('election_name')->index();
            $table->string('election_type');
            $table->unsignedInteger('constituency_id')->nullable();
            $table->unsignedInteger('total_votes')->default(0);
            $table->unsignedInteger('registered_voters')->default(0);
            $table->decimal('turnout', 5, 2)->nullable();
            $table->enum('status', ['active', 'inactive', 'trash'])->default('active')->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('nec_candidate_results', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('result_id')->index();
            $table->unsignedInteger('candidate_id')->nullable()->index();
            $table->string('candidate_name')->nullable();
            $table->unsignedInteger('party_id')->nullable();
            $table->string('party_name')->nullable();
            $table->unsignedInteger('votes')->default(0);
            $table->decimal('percentage', 5, 2)->nullable();
            $table->enum('status', ['active', 'inactive', 'trash'])->default('active');
        });

        Schema::create('nec_ballots', function (Blueprint $table) {
            $table->id();
            $table->string('election_name')->index();
            $table->string('election_type');
            $table->string('constituency')->nullable();
            $table->string('state', 100)->nullable();
            $table->text('ballot_design')->nullable();
            $table->text('candidates')->nullable();
            $table->unsignedInteger('total_printed')->default(0);
            $table->string('serial_start', 50)->nullable();
            $table->string('serial_end', 50)->nullable();
            $table->string('printer')->nullable();
            $table->date('delivery_date')->nullable();
            $table->date('received_date')->nullable();
            $table->enum('status', ['designing','printing','delivered','received','used','audited','spoiled'])->default('designing')->index();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('nec_nominations', function (Blueprint $table) {
            $table->id();
            $table->string('candidate_name');
            $table->unsignedInteger('party_id')->nullable()->index();
            $table->string('position');
            $table->string('constituency')->nullable();
            $table->string('state', 100)->nullable();
            $table->string('nominator_name')->nullable();
            $table->text('nominator_signature')->nullable();
            $table->text('documents')->nullable();
            $table->date('nomination_date')->nullable();
            $table->enum('status', ['draft','submitted','verified','approved','rejected','withdrawn'])->default('draft')->index();
            $table->string('reviewed_by')->nullable();
            $table->datetime('reviewed_at')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });

        Schema::create('nec_election_petitions', function (Blueprint $table) {
            $table->id();
            $table->string('petition_number', 50)->unique();
            $table->string('petitioner_name');
            $table->string('respondent_name')->nullable();
            $table->string('election_name')->nullable();
            $table->string('constituency')->nullable();
            $table->date('filing_date')->nullable();
            $table->text('grounds');
            $table->text('relief_sought')->nullable();
            $table->string('court_name')->nullable();
            $table->string('case_number', 100)->nullable();
            $table->text('verdict')->nullable();
            $table->date('verdict_date')->nullable();
            $table->enum('status', ['filed','pending_hearing','in_progress','decided','closed','appealed'])->default('filed')->index();
            $table->timestamps();
        });

        Schema::create('nec_polling_staff', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('email')->nullable();
            $table->string('phone', 50)->nullable();
            $table->enum('role', ['presiding_officer','deputy_presiding','poll_clerk','security','observer','trainer'])->default('poll_clerk')->index();
            $table->unsignedInteger('polling_station_id')->nullable()->index();
            $table->string('state', 100)->nullable();
            $table->string('constituency', 100)->nullable();
            $table->date('assignment_date')->nullable();
            $table->boolean('trained')->default(false);
            $table->enum('status', ['active','inactive','suspended','trash'])->default('active')->index();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('nec_announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title')->index();
            $table->string('slug')->nullable()->unique();
            $table->longText('content')->nullable();
            $table->text('excerpt')->nullable();
            $table->string('type', 50)->default('general')->index();
            $table->string('author')->nullable();
            $table->string('image', 500)->nullable();
            $table->enum('status', ['published','draft','trash'])->default('draft')->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('nec_news', function (Blueprint $table) {
            $table->id();
            $table->string('title')->index();
            $table->string('slug')->nullable()->unique();
            $table->longText('content')->nullable();
            $table->text('excerpt')->nullable();
            $table->string('category', 100)->default('news')->index();
            $table->string('author')->nullable();
            $table->string('image', 500)->nullable();
            $table->text('tags')->nullable();
            $table->unsignedInteger('views')->default(0);
            $table->enum('status', ['published','draft','trash'])->default('draft')->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('nec_cms_pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('content')->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->string('template', 100)->default('default');
            $table->enum('status', ['published','draft','trash'])->default('draft')->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('nec_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('slug', 100)->nullable()->unique();
            $table->text('description')->nullable();
            $table->unsignedInteger('parent_id')->default(0)->index();
            $table->enum('status', ['active','inactive','trash'])->default('active');
            $table->timestamps();
        });

        Schema::create('nec_tags', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('slug', 100)->nullable()->unique();
            $table->enum('status', ['active','inactive','trash'])->default('active');
            $table->timestamps();
        });

        Schema::create('nec_comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('post_id')->nullable()->index();
            $table->string('author_name');
            $table->string('author_email')->nullable();
            $table->text('content');
            $table->enum('status', ['approved','pending','spam','trash'])->default('pending')->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('nec_commissioners', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('position');
            $table->text('bio')->nullable();
            $table->string('photo', 500)->nullable();
            $table->integer('order_num')->default(0)->index();
            $table->enum('status', ['active','inactive','trash'])->default('active')->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('nec_speeches', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('speaker')->nullable()->index();
            $table->longText('content')->nullable();
            $table->string('event_name')->nullable();
            $table->date('speech_date')->nullable()->index();
            $table->string('document_url', 500)->nullable();
            $table->enum('status', ['published','draft','trash'])->default('draft')->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('nec_media', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('url', 500);
            $table->enum('type', ['video','image','audio','document'])->default('video')->index();
            $table->string('thumbnail', 500)->nullable();
            $table->unsignedInteger('views')->default(0);
            $table->unsignedInteger('likes')->default(0);
            $table->enum('status', ['published','draft','trash'])->default('published')->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('nec_gallery', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('image_path', 500);
            $table->string('album', 100)->default('general')->index();
            $table->enum('status', ['published','draft','trash'])->default('published')->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('nec_education_materials', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_path', 500)->nullable();
            $table->enum('content_type', ['document','video','infographic','poster','presentation','other'])->default('document')->index();
            $table->string('language', 50)->default('English');
            $table->string('target_audience', 100)->default('general');
            $table->enum('status', ['published','draft','trash'])->default('draft')->index();
            $table->timestamps();
        });

        Schema::create('nec_complaints', function (Blueprint $table) {
            $table->id();
            $table->string('voter_identifier', 50)->nullable();
            $table->string('full_name');
            $table->string('email')->nullable();
            $table->string('phone', 50)->nullable();
            $table->enum('category', ['registration','voter_card','polling_station','results','observer','staff','other'])->default('other')->index();
            $table->string('subject');
            $table->text('description');
            $table->string('attachment', 500)->nullable();
            $table->enum('status', ['new','open','in_progress','resolved','closed','escalated'])->default('new')->index();
            $table->enum('priority', ['low','medium','high','urgent'])->default('medium');
            $table->string('assigned_to')->nullable();
            $table->text('resolution')->nullable();
            $table->datetime('resolved_at')->nullable();
            $table->string('resolved_by')->nullable();
            $table->timestamps();
        });

        Schema::create('nec_contacts', function (Blueprint $table) {
            $table->id();
            $table->string('form_type', 50)->default('standard');
            $table->string('name');
            $table->string('email')->index();
            $table->string('phone', 50)->nullable();
            $table->string('subject')->nullable();
            $table->string('topic', 100)->nullable();
            $table->text('message');
            $table->text('admin_reply')->nullable();
            $table->datetime('replied_at')->nullable();
            $table->string('replied_by')->nullable();
            $table->enum('status', ['new','read','replied','closed','trash'])->default('new')->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('nec_downloads', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_path', 500);
            $table->string('file_size', 20)->nullable();
            $table->string('file_type', 50)->nullable();
            $table->string('category', 100)->default('general')->index();
            $table->unsignedInteger('downloads_count')->default(0);
            $table->enum('status', ['published','draft','trash'])->default('published')->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('nec_download_stats', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('label');
            $table->string('url', 500);
            $table->unsignedInteger('count')->default(0);
            $table->timestamps();
        });

        Schema::create('nec_reports', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_path', 500)->nullable();
            $table->string('category', 100)->default('general')->index();
            $table->date('report_date')->nullable()->index();
            $table->enum('status', ['published','draft','trash'])->default('published')->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('nec_faq', function (Blueprint $table) {
            $table->id();
            $table->string('category', 100)->default('general')->index();
            $table->text('question');
            $table->text('answer');
            $table->integer('sort_order')->default(0)->index();
            $table->enum('status', ['published','draft','trash'])->default('published')->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('nec_observer_applications', function (Blueprint $table) {
            $table->id();
            $table->string('title', 50)->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('other_names')->nullable();
            $table->enum('gender', ['male','female','other'])->nullable();
            $table->date('dob')->nullable();
            $table->string('nationality', 100)->nullable();
            $table->string('national_id', 100)->nullable();
            $table->string('email');
            $table->string('phone', 50)->nullable();
            $table->text('residential_address')->nullable();
            $table->string('postal_address')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone', 50)->nullable();
            $table->string('employer')->nullable();
            $table->string('job_title')->nullable();
            $table->string('employment_duration', 100)->nullable();
            $table->string('organization_name')->nullable();
            $table->string('organization_registration', 100)->nullable();
            $table->text('org_address')->nullable();
            $table->enum('observer_type', ['domestic','international','regional'])->nullable();
            $table->string('sponsoring_org')->nullable();
            $table->unsignedInteger('observer_count')->default(1);
            $table->text('deployment_areas')->nullable();
            $table->text('previous_missions')->nullable();
            $table->text('election_experience')->nullable();
            $table->text('languages')->nullable();
            $table->string('letter_of_appointment', 500)->nullable();
            $table->string('cv_biography', 500)->nullable();
            $table->string('code_of_conduct', 500)->nullable();
            $table->string('passport_photo', 500)->nullable();
            $table->string('proof_registration', 500)->nullable();
            $table->enum('status', ['pending','reviewing','approved','rejected'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });

        Schema::create('nec_observers', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('last_name');
            $table->string('other_names');
            $table->string('title', 50)->nullable();
            $table->enum('gender', ['male','female'])->nullable();
            $table->string('national_id', 100)->nullable();
            $table->string('phone', 50)->nullable();
            $table->text('residential_address')->nullable();
            $table->enum('observer_type', ['individual','organisation'])->default('individual');
            $table->enum('category', ['domestic','international','regional'])->default('domestic')->index();
            $table->string('nationality', 100)->default('South Sudanese');
            $table->string('organisation_name')->nullable();
            $table->string('organisation_code', 100)->nullable();
            $table->string('sponsoring_org')->nullable();
            $table->enum('status', ['pending','verified','accredited','rejected','trash'])->default('pending')->index();
            $table->string('verification_token', 100)->nullable();
            $table->datetime('verified_at')->nullable();
            $table->datetime('accredited_at')->nullable();
            $table->text('admin_notes')->nullable();
            $table->string('photo', 500)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('nec_api_keys', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('api_key', 64)->unique();
            $table->string('organization')->nullable();
            $table->string('contact_email')->nullable();
            $table->set('permissions', ['verify','results','parties','candidates','voters','all'])->default('verify');
            $table->text('allowed_ips')->nullable();
            $table->unsignedInteger('rate_limit')->default(1000);
            $table->enum('status', ['active','inactive','revoked'])->default('active')->index();
            $table->datetime('last_used_at')->nullable();
            $table->datetime('expires_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('nec_assets', function (Blueprint $table) {
            $table->id();
            $table->enum('asset_type', ['ballot_box','seal','stamp','ink','form','other'])->default('other')->index();
            $table->string('serial_number', 100)->nullable()->index();
            $table->text('description')->nullable();
            $table->unsignedInteger('quantity')->default(1);
            $table->string('assigned_to')->nullable();
            $table->string('location')->nullable();
            $table->enum('status', ['in_stock','issued','used','lost','damaged','returned'])->default('in_stock')->index();
            $table->string('tracked_by')->nullable();
            $table->timestamps();
        });

        Schema::create('nec_election_events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('event_type', 100)->index();
            $table->datetime('start_date')->index();
            $table->datetime('end_date')->nullable();
            $table->string('location')->nullable();
            $table->enum('status', ['active','inactive','trash'])->default('active')->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('nec_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->string('user_email')->index();
            $table->string('action', 50)->index();
            $table->string('entity_type', 100)->index();
            $table->unsignedInteger('entity_id')->nullable();
            $table->text('details')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('nec_security_logs', function (Blueprint $table) {
            $table->id();
            $table->string('event_type', 50)->default('other')->index();
            $table->text('details')->nullable();
            $table->text('request_data')->nullable();
            $table->string('ip_address', 45)->default('')->index();
            $table->string('user_agent', 500)->default('');
            $table->string('request_uri', 500)->default('');
            $table->string('user_email', 255)->default('guest');
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('nec_sequences', function (Blueprint $table) {
            $table->string('seq_name', 50)->primary();
            $table->unsignedBigInteger('seq_value')->default(0);
        });

        Schema::create('nec_settings', function (Blueprint $table) {
            $table->string('key', 100)->primary();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        Schema::create('nec_subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('source', 100)->default('newsletter');
            $table->enum('status', ['active','inactive','trash'])->default('active')->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('nec_menus', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('location', 50)->unique();
            $table->longText('items')->nullable();
            $table->enum('status', ['active','inactive'])->default('active');
            $table->timestamps();
        });

        Schema::create('nec_widgets', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('area', 50)->index();
            $table->string('type', 50);
            $table->longText('config')->nullable();
            $table->integer('order_num')->default(0);
            $table->enum('status', ['active','inactive'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // Drop in reverse dependency order
        Schema::dropIfExists('nec_widgets');
        Schema::dropIfExists('nec_menus');
        Schema::dropIfExists('nec_subscribers');
        Schema::dropIfExists('nec_settings');
        Schema::dropIfExists('nec_sequences');
        Schema::dropIfExists('nec_security_logs');
        Schema::dropIfExists('nec_activity_logs');
        Schema::dropIfExists('nec_election_events');
        Schema::dropIfExists('nec_assets');
        Schema::dropIfExists('nec_api_keys');
        Schema::dropIfExists('nec_observers');
        Schema::dropIfExists('nec_observer_applications');
        Schema::dropIfExists('nec_faq');
        Schema::dropIfExists('nec_reports');
        Schema::dropIfExists('nec_download_stats');
        Schema::dropIfExists('nec_downloads');
        Schema::dropIfExists('nec_contacts');
        Schema::dropIfExists('nec_complaints');
        Schema::dropIfExists('nec_education_materials');
        Schema::dropIfExists('nec_gallery');
        Schema::dropIfExists('nec_media');
        Schema::dropIfExists('nec_speeches');
        Schema::dropIfExists('nec_comments');
        Schema::dropIfExists('nec_commissioners');
        Schema::dropIfExists('nec_tags');
        Schema::dropIfExists('nec_categories');
        Schema::dropIfExists('nec_cms_pages');
        Schema::dropIfExists('nec_news');
        Schema::dropIfExists('nec_announcements');
        Schema::dropIfExists('nec_polling_staff');
        Schema::dropIfExists('nec_election_petitions');
        Schema::dropIfExists('nec_nominations');
        Schema::dropIfExists('nec_ballots');
        Schema::dropIfExists('nec_candidate_results');
        Schema::dropIfExists('nec_results');
        Schema::dropIfExists('nec_candidates');
        Schema::dropIfExists('nec_political_parties');
        Schema::dropIfExists('nec_agents');
        Schema::dropIfExists('nec_voter_transfers');
        Schema::dropIfExists('nec_voter_accounts');
        Schema::dropIfExists('nec_voters');
        Schema::dropIfExists('nec_role_permissions');
        Schema::dropIfExists('nec_permissions');
        Schema::dropIfExists('nec_users');
        Schema::dropIfExists('nec_polling_stations');
        Schema::dropIfExists('nec_bomas');
        Schema::dropIfExists('nec_payams');
        Schema::dropIfExists('nec_constituencies');
        Schema::dropIfExists('nec_counties');
        Schema::dropIfExists('nec_states');
        Schema::dropIfExists('nec_regions');
    }
};
