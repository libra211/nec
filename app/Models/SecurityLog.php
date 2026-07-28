<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecurityLog extends Model
{
    public $timestamps = false;
    use HasFactory;

    protected $table = 'nec_security_logs';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'ip_address' => 'string',
            'created_at' => 'datetime',
        ];
    }
}
