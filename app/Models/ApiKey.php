<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiKey extends Model
{
    public $timestamps = false;
    use HasFactory;

    protected $table = 'nec_api_keys';

    protected $guarded = [];

    protected $hidden = [
        'key',
    ];

    protected function casts(): array
    {
        return [
            'permissions' => 'json',
            'last_used_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }
}
