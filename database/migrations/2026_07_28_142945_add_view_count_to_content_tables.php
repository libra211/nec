<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add views and featured_image to announcements
        Schema::table('nec_announcements', function (Blueprint $table) {
            if (!Schema::hasColumn('nec_announcements', 'views')) {
                $table->unsignedInteger('views')->default(0)->after('image');
            }
            if (!Schema::hasColumn('nec_announcements', 'featured_image')) {
                $table->string('featured_image', 500)->nullable()->after('image');
            }
            if (!Schema::hasColumn('nec_announcements', 'meta_description')) {
                $table->string('meta_description', 500)->nullable()->after('excerpt');
            }
            if (!Schema::hasColumn('nec_announcements', 'published_at')) {
                $table->timestamp('published_at')->nullable()->after('status');
            }
        });

        // Add views and featured_image to gallery
        Schema::table('nec_gallery', function (Blueprint $table) {
            if (!Schema::hasColumn('nec_gallery', 'views')) {
                $table->unsignedInteger('views')->default(0)->after('album');
            }
            if (!Schema::hasColumn('nec_gallery', 'featured_image')) {
                $table->string('featured_image', 500)->nullable()->after('image_path');
            }
            if (!Schema::hasColumn('nec_gallery', 'meta_description')) {
                $table->string('meta_description', 500)->nullable()->after('description');
            }
            if (!Schema::hasColumn('nec_gallery', 'published_at')) {
                $table->timestamp('published_at')->nullable()->after('status');
            }
        });

        // Add views to education materials
        Schema::table('nec_education_materials', function (Blueprint $table) {
            if (!Schema::hasColumn('nec_education_materials', 'views')) {
                $table->unsignedInteger('views')->default(0)->after('file_path');
            }
            if (!Schema::hasColumn('nec_education_materials', 'featured_image')) {
                $table->string('featured_image', 500)->nullable()->after('file_path');
            }
            if (!Schema::hasColumn('nec_education_materials', 'meta_description')) {
                $table->string('meta_description', 500)->nullable()->after('description');
            }
            if (!Schema::hasColumn('nec_education_materials', 'published_at')) {
                $table->timestamp('published_at')->nullable()->after('status');
            }
        });

        // Add views to speeches
        Schema::table('nec_speeches', function (Blueprint $table) {
            if (!Schema::hasColumn('nec_speeches', 'views')) {
                $table->unsignedInteger('views')->default(0)->after('document_url');
            }
            if (!Schema::hasColumn('nec_speeches', 'featured_image')) {
                $table->string('featured_image', 500)->nullable()->after('document_url');
            }
            if (!Schema::hasColumn('nec_speeches', 'meta_description')) {
                $table->string('meta_description', 500)->nullable()->after('content');
            }
            if (!Schema::hasColumn('nec_speeches', 'published_at')) {
                $table->timestamp('published_at')->nullable()->after('status');
            }
        });

        // Add featured_image and meta_description to news (views already exists)
        Schema::table('nec_news', function (Blueprint $table) {
            if (!Schema::hasColumn('nec_news', 'featured_image')) {
                $table->string('featured_image', 500)->nullable()->after('image');
            }
            if (!Schema::hasColumn('nec_news', 'meta_description')) {
                $table->string('meta_description', 500)->nullable()->after('excerpt');
            }
            if (!Schema::hasColumn('nec_news', 'published_at')) {
                $table->timestamp('published_at')->nullable()->after('status');
            }
        });

        // Create events table if not exists
        if (!Schema::hasTable('nec_events')) {
            Schema::create('nec_events', function (Blueprint $table) {
                $table->id();
                $table->string('title')->index();
                $table->string('slug')->nullable()->unique();
                $table->longText('description')->nullable();
                $table->string('location')->nullable();
                $table->dateTime('start_date');
                $table->dateTime('end_date')->nullable();
                $table->string('organizer')->nullable();
                $table->string('event_type', 50)->default('public')->index();
                $table->string('featured_image', 500)->nullable();
                $table->text('meta_description')->nullable();
                $table->string('status', 20)->default('draft')->index();
                $table->unsignedInteger('views')->default(0);
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    public function down(): void
    {
        // No down migration - too complex to reverse each column
    }
};
