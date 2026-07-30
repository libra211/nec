<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class GalleryAlbum extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'gallery_albums';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $album) {
            if (!$album->slug) {
                $album->slug = Str::slug($album->title);
            }
            $album->published_at ??= now();
        });
    }

    public function images()
    {
        return $this->hasMany(Gallery::class, 'gallery_album_id');
    }

    public function publishedImages()
    {
        return $this->images()->where('status', 'published');
    }

    public function scopePublished($q)
    {
        $q->where('status', 'published');
    }
}
