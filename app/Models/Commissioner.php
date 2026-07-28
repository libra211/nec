<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commissioner extends Model
{
    public $timestamps = false;
    use HasFactory;

    protected $table = 'nec_commissioners';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'appointed_date' => 'date',
        ];
    }
}
