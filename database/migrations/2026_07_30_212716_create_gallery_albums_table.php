<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gallery_albums', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->nullable()->unique();
            $table->text('description')->nullable();
            $table->string('featured_image', 500)->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->enum('status', ['published', 'draft', 'trash'])->default('published');
            $table->integer('views')->unsigned()->default(0);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Alter nec_gallery: add FK and new columns
        Schema::table('nec_gallery', function (Blueprint $table) {
            $table->foreignId('gallery_album_id')->nullable()->constrained('gallery_albums')->cascadeOnDelete();
            $table->string('alt_text', 255)->nullable();
            $table->integer('sort_order')->default(0);
        });

        // Drop old indexes before dropping column
        Schema::table('nec_gallery', function (Blueprint $table) {
            $table->dropIndex(['album']);
        });

        // Migrate existing data: group nec_gallery by album, create album per group
        $images = DB::table('nec_gallery')->whereNotNull('album')->get()->groupBy('album');
        foreach ($images as $albumName => $items) {
            $first = $items->first();
            $albumId = DB::table('gallery_albums')->insertGetId([
                'title' => ucwords(str_replace(['-', '_'], ' ', $albumName)),
                'slug' => $albumName,
                'description' => $first->description,
                'featured_image' => $first->featured_image ?: $first->image_path,
                'meta_description' => $first->meta_description,
                'status' => $first->status,
                'views' => $items->sum('views'),
                'created_at' => $first->created_at,
                'updated_at' => $first->updated_at,
                'deleted_at' => $first->deleted_at,
            ]);

            foreach ($items as $i => $item) {
                DB::table('nec_gallery')->where('id', $item->id)->update([
                    'gallery_album_id' => $albumId,
                    'alt_text' => $item->title,
                    'sort_order' => $i,
                ]);
            }
        }

        // Handle images with no album (null or empty)
        $orphans = DB::table('nec_gallery')->whereNull('gallery_album_id')->get();
        if ($orphans->isNotEmpty()) {
            $first = $orphans->first();
            $albumId = DB::table('gallery_albums')->insertGetId([
                'title' => 'General',
                'slug' => 'general',
                'description' => $first->description,
                'featured_image' => $first->featured_image ?: $first->image_path,
                'status' => $first->status,
                'created_at' => $first->created_at,
                'updated_at' => $first->updated_at,
            ]);
            foreach ($orphans as $i => $item) {
                DB::table('nec_gallery')->where('id', $item->id)->update([
                    'gallery_album_id' => $albumId,
                    'alt_text' => $item->title,
                    'sort_order' => $i,
                ]);
            }
        }

        // Now drop the old album column
        Schema::table('nec_gallery', function (Blueprint $table) {
            $table->dropColumn('album');
        });
    }

    public function down(): void
    {
        // Re-add album column and index
        Schema::table('nec_gallery', function (Blueprint $table) {
            $table->string('album', 100)->default('general')->after('featured_image');
            $table->index('album');
        });

        // Restore album name from slug
        DB::statement('UPDATE nec_gallery n JOIN gallery_albums a ON n.gallery_album_id = a.id SET n.album = COALESCE(a.slug, "general")');

        Schema::table('nec_gallery', function (Blueprint $table) {
            $table->dropForeign(['gallery_album_id']);
            $table->dropColumn(['gallery_album_id', 'alt_text', 'sort_order']);
        });

        Schema::dropIfExists('gallery_albums');
    }
};
