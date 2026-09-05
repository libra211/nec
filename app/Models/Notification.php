<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'nec_notifications';

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'link',
        'icon',
        'color',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function scopeUnread(Builder $query): Builder
    {
        return $query->whereNull('read_at');
    }

    public function scopeForAdmins(Builder $query, ?int $userId = null): Builder
    {
        return $query->where(function (Builder $query) use ($userId) {
            $query->whereNull('user_id');
            if ($userId) {
                $query->orWhere('user_id', $userId);
            }
        });
    }

    public static function notifyAdmins(string $message, array $options = []): self
    {
        return static::create([
            'user_id' => $options['user_id'] ?? null,
            'type' => $options['type'] ?? 'info',
            'title' => $options['title'] ?? 'NEC Update',
            'message' => $message,
            'link' => $options['link'] ?? null,
            'icon' => $options['icon'] ?? 'bell',
            'color' => $options['color'] ?? 'primary',
        ]);
    }

    public function markRead(): void
    {
        if ($this->read_at === null) {
            $this->update(['read_at' => now()]);
        }
    }
}