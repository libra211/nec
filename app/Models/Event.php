<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'nec_events';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'start_date' => 'datetime',
            'end_date' => 'datetime',
            'views' => 'integer',
        ];
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeTrashed($query)
    {
        return $query->where('status', 'trash');
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function getExcerptAttribute($value)
    {
        return $value ?? Str::limit(strip_tags($this->description ?? ''), 200);
    }

    public function getUrlAttribute(): string
    {
        return route('events.show', $this->slug ?? $this->id);
    }

    public function incrementViews(): void
    {
        $this->increment('views');
    }
}
