<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginLog extends Model
{
    protected $table = 'login_logs';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'success' => 'boolean',
            'logged_at' => 'datetime',
        ];
    }
}
