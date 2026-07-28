<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $table = 'nec_permissions';

    protected $fillable = ['slug', 'name', 'module', 'description', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function roles()
    {
        return $this->belongsToMany(
            self::class,
            'nec_role_permissions',
            'permission_id',
            'role'
        );
    }

    public static function forRole(string $role)
    {
        return cache()->remember("permissions_{$role}", 3600, function () use ($role) {
            return static::where('nec_permissions.is_active', true)
                ->whereIn('nec_permissions.id', function ($q) use ($role) {
                    $q->select('permission_id')
                        ->from('nec_role_permissions')
                        ->where('role', $role);
                })
                ->pluck('slug')
                ->toArray();
        });
    }

    public static function flushCache(?string $role = null): void
    {
        if ($role) {
            cache()->forget("permissions_{$role}");
        } else {
            foreach (['super_admin', 'admin', 'state_coordinator', 'constituency_officer',
                       'registration_officer', 'polling_officer', 'data_entry', 'content_editor', 'viewer'] as $r) {
                cache()->forget("permissions_{$r}");
            }
        }
    }
}
