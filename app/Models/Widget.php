<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Widget extends Model
{
    use HasFactory;

    protected $table = 'nec_widgets';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'config' => 'json',
        ];
    }
}
