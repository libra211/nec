<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('nec_commissioners', function (Blueprint $table) {
            $table->string('title', 20)->nullable()->after('name');
            $table->string('gender', 10)->nullable()->after('title');
            $table->date('date_of_birth')->nullable()->after('gender');
            $table->string('nationality', 100)->nullable()->after('date_of_birth');
            $table->string('email', 255)->nullable()->after('nationality');
            $table->string('phone', 30)->nullable()->after('email');
            $table->text('about')->nullable()->after('bio');
            $table->text('experience')->nullable()->after('about');
            $table->text('qualifications')->nullable()->after('experience');
            $table->text('achievements')->nullable()->after('qualifications');
            $table->string('facebook_url', 500)->nullable()->after('achievements');
            $table->string('twitter_url', 500)->nullable()->after('facebook_url');
            $table->string('linkedin_url', 500)->nullable()->after('twitter_url');
            $table->string('website_url', 500)->nullable()->after('linkedin_url');
            $table->integer('years_of_service')->nullable()->after('website_url');
            $table->string('department', 255)->nullable()->after('years_of_service');
            $table->boolean('featured')->default(false)->after('department');
        });
    }

    public function down(): void
    {
        Schema::table('nec_commissioners', function (Blueprint $table) {
            $table->dropColumn([
                'title', 'gender', 'date_of_birth', 'nationality', 'email', 'phone',
                'about', 'experience', 'qualifications', 'achievements',
                'facebook_url', 'twitter_url', 'linkedin_url', 'website_url',
                'years_of_service', 'department', 'featured',
            ]);
        });
    }
};
