<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DashboardVisibility extends Model
{
    protected $table = 'dashboard_visibility';

    protected $fillable = ['role', 'key', 'visible'];

    protected $casts = [
        'visible' => 'boolean',
    ];
}