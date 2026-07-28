<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObserverApplication extends Model
{
    use HasFactory;

    protected $table = 'nec_observer_applications';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'dob' => 'date',
            'observer_count' => 'integer',
        ];
    }
}
